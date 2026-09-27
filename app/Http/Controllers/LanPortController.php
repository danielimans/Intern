<?php

namespace App\Http\Controllers;

use App\Models\LanPort;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class LanPortController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of LAN ports.
     */
    public function index(Request $request)
    {
        $query = LanPort::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->byStatus($request->status);
        }

        // Filter by floor
        if ($request->has('floor') && $request->floor !== '') {
            $query->byFloor($request->floor);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('wall_port_label', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('switch_port', 'like', "%{$search}%");
            });
        }

        $lanPorts = $query->paginate(15);

        // Log the view
        $this->auditLogService->log('view', 'LanPort', null);

        return view('lan-ports.index', compact('lanPorts'));
    }

    /**
     * Show the form for creating a new LAN port.
     */
    public function create()
    {
        $users = User::active()->get();
        return view('lan-ports.create', compact('users'));
    }

    /**
     * Store a newly created LAN port in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wall_port_label' => 'required|string|unique:lan_ports',
            'user_id' => 'nullable|exists:users,id',
            'extension_number' => 'nullable|string',
            'email' => 'nullable|email',
            'switch_port' => 'required|string',
            'floor_user' => 'required|string',
            'port_status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
        ]);

        $lanPort = LanPort::create($validated);

        // Log the creation
        $this->auditLogService->log('create', 'LanPort', $lanPort->id, null, $lanPort->toArray());

        return redirect()->route('lan-ports.index')->with('success', 'LAN port created successfully');
    }

    /**
     * Display the specified LAN port.
     */
    public function show(LanPort $lanPort)
    {
        $this->auditLogService->log('view', 'LanPort', $lanPort->id);
        return view('lan-ports.show', compact('lanPort'));
    }

    /**
     * Show the form for editing the specified LAN port.
     */
    public function edit(LanPort $lanPort)
    {
        $users = User::active()->get();
        return view('lan-ports.edit', compact('lanPort', 'users'));
    }

    /**
     * Update the specified LAN port in storage.
     */
    public function update(Request $request, LanPort $lanPort)
    {
        $validated = $request->validate([
            'wall_port_label' => 'required|string|unique:lan_ports,wall_port_label,' . $lanPort->id,
            'user_id' => 'nullable|exists:users,id',
            'extension_number' => 'nullable|string',
            'email' => 'nullable|email',
            'switch_port' => 'required|string',
            'floor_user' => 'required|string',
            'port_status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $lanPort->toArray();
        $lanPort->update($validated);

        // Log the update
        $this->auditLogService->log('update', 'LanPort', $lanPort->id, $oldValues, $lanPort->toArray());

        return redirect()->route('lan-ports.index')->with('success', 'LAN port updated successfully');
    }

    /**
     * Remove the specified LAN port from storage.
     */
    public function destroy(LanPort $lanPort)
    {
        $oldValues = $lanPort->toArray();
        $lanPort->delete();

        // Log the deletion
        $this->auditLogService->log('delete', 'LanPort', $lanPort->id, $oldValues, null);

        return redirect()->route('lan-ports.index')->with('success', 'LAN port deleted successfully');
    }

    /**
     * Export LAN ports to CSV.
     */
    public function export()
    {
        $this->auditLogService->log('export', 'LanPort', null);

        $lanPorts = LanPort::all();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=lan_ports_' . now()->format('Y-m-d_His') . '.csv',
        ];

        $callback = function () use ($lanPorts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Wall Port Label', 'User', 'Extension', 'Email', 'Switch Port', 'Floor', 'Status', 'Created At']);

            foreach ($lanPorts as $port) {
                fputcsv($file, [
                    $port->wall_port_label,
                    $port->user?->full_name ?? 'N/A',
                    $port->extension_number,
                    $port->email,
                    $port->switch_port,
                    $port->floor_user,
                    $port->port_status,
                    $port->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update port status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'port_ids' => 'required|array',
            'port_ids.*' => 'exists:lan_ports,id',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $ports = LanPort::whereIn('id', $validated['port_ids'])->get();

        foreach ($ports as $port) {
            $oldValues = $port->toArray();
            $port->update(['port_status' => $validated['status']]);
            $this->auditLogService->log('update', 'LanPort', $port->id, $oldValues, $port->toArray());
        }

        return response()->json(['success' => true, 'message' => 'Ports updated successfully']);
    }
}
