<?php

namespace App\Http\Controllers;

use App\Models\VoicePort;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class VoicePortController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of voice ports.
     */
    public function index(Request $request)
    {
        $query = VoicePort::query();

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
                  ->orWhere('pr_number', 'like', "%{$search}%")
                  ->orWhere('pen_number', 'like', "%{$search}%");
            });
        }

        $voicePorts = $query->paginate(15);

        // Log the view
        $this->auditLogService->log('view', 'VoicePort', null);

        return view('voice-ports.index', compact('voicePorts'));
    }

    /**
     * Show the form for creating a new voice port.
     */
    public function create()
    {
        $users = User::active()->get();
        return view('voice-ports.create', compact('users'));
    }

    /**
     * Store a newly created voice port in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wall_port_label' => 'required|string|unique:voice_ports',
            'user_id' => 'nullable|exists:users,id',
            'extension_number' => 'required|string',
            'email' => 'nullable|email',
            'pr_number' => 'required|string|unique:voice_ports',
            'pen_number' => 'required|string|unique:voice_ports',
            'floor_user' => 'required|string',
            'port_status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
        ]);

        // Validate PR and PEN numbers (customize based on your format)
        $this->validatePrPenNumbers($validated);

        $voicePort = VoicePort::create($validated);

        // Log the creation
        $this->auditLogService->log('create', 'VoicePort', $voicePort->id, null, $voicePort->toArray());

        return redirect()->route('voice-ports.index')->with('success', 'Voice port created successfully');
    }

    /**
     * Display the specified voice port.
     */
    public function show(VoicePort $voicePort)
    {
        $this->auditLogService->log('view', 'VoicePort', $voicePort->id);
        return view('voice-ports.show', compact('voicePort'));
    }

    /**
     * Show the form for editing the specified voice port.
     */
    public function edit(VoicePort $voicePort)
    {
        $users = User::active()->get();
        return view('voice-ports.edit', compact('voicePort', 'users'));
    }

    /**
     * Update the specified voice port in storage.
     */
    public function update(Request $request, VoicePort $voicePort)
    {
        $validated = $request->validate([
            'wall_port_label' => 'required|string|unique:voice_ports,wall_port_label,' . $voicePort->id,
            'user_id' => 'nullable|exists:users,id',
            'extension_number' => 'required|string',
            'email' => 'nullable|email',
            'pr_number' => 'required|string|unique:voice_ports,pr_number,' . $voicePort->id,
            'pen_number' => 'required|string|unique:voice_ports,pen_number,' . $voicePort->id,
            'floor_user' => 'required|string',
            'port_status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
        ]);

        // Validate PR and PEN numbers
        $this->validatePrPenNumbers($validated);

        $oldValues = $voicePort->toArray();
        $voicePort->update($validated);

        // Log the update
        $this->auditLogService->log('update', 'VoicePort', $voicePort->id, $oldValues, $voicePort->toArray());

        return redirect()->route('voice-ports.index')->with('success', 'Voice port updated successfully');
    }

    /**
     * Remove the specified voice port from storage.
     */
    public function destroy(VoicePort $voicePort)
    {
        $oldValues = $voicePort->toArray();
        $voicePort->delete();

        // Log the deletion
        $this->auditLogService->log('delete', 'VoicePort', $voicePort->id, $oldValues, null);

        return redirect()->route('voice-ports.index')->with('success', 'Voice port deleted successfully');
    }

    /**
     * Export voice ports to CSV.
     */
    public function export()
    {
        $this->auditLogService->log('export', 'VoicePort', null);

        $voicePorts = VoicePort::all();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=voice_ports_' . now()->format('Y-m-d_His') . '.csv',
        ];

        $callback = function () use ($voicePorts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Wall Port Label', 'User', 'Extension', 'Email', 'PR Number', 'PEN Number', 'Floor', 'Status', 'Created At']);

            foreach ($voicePorts as $port) {
                fputcsv($file, [
                    $port->wall_port_label,
                    $port->user?->full_name ?? 'N/A',
                    $port->extension_number,
                    $port->email,
                    $port->pr_number,
                    $port->pen_number,
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
            'port_ids.*' => 'exists:voice_ports,id',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $ports = VoicePort::whereIn('id', $validated['port_ids'])->get();

        foreach ($ports as $port) {
            $oldValues = $port->toArray();
            $port->update(['port_status' => $validated['status']]);
            $this->auditLogService->log('update', 'VoicePort', $port->id, $oldValues, $port->toArray());
        }

        return response()->json(['success' => true, 'message' => 'Ports updated successfully']);
    }

    /**
     * Validate PR and PEN number formats.
     */
    private function validatePrPenNumbers($data)
    {
        // Customize these validation rules based on your format requirements
        if (!preg_match('/^[A-Z0-9\-]{5,}$/', $data['pr_number'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'pr_number' => ['Invalid PR number format. Expected format: PR-XXXXX'],
            ]);
        }

        if (!preg_match('/^[A-Z0-9\-]{5,}$/', $data['pen_number'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'pen_number' => ['Invalid PEN number format. Expected format: PEN-XXXXX'],
            ]);
        }
    }
}
