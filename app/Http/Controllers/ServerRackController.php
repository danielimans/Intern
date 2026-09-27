<?php

namespace App\Http\Controllers;

use App\Models\ServerRack;
use App\Models\RackUnit;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class ServerRackController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of server racks.
     */
    public function index()
    {
        $serverRacks = ServerRack::all();
        $this->auditLogService->log('view', 'ServerRack', null);
        
        return view('server-racks.index', compact('serverRacks'));
    }

    /**
     * Show the 360-degree visualization of a server rack.
     */
    public function visualize(ServerRack $serverRack)
    {
        $vizData = $serverRack->getVisualizationData();
        $this->auditLogService->log('view', 'ServerRack', $serverRack->id);

        return view('server-racks.visualize', [
            'rack' => $serverRack,
            'vizData' => json_encode($vizData),
        ]);
    }

    /**
     * Get rack visualization data for API/AJAX.
     */
    public function getVisualization(ServerRack $serverRack)
    {
        return response()->json($serverRack->getVisualizationData());
    }

    /**
     * Show form for creating a new server rack.
     */
    public function create()
    {
        return view('server-racks.create');
    }

    /**
     * Store a new server rack.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rack_name' => 'required|string',
            'location' => 'required|string',
            'total_units' => 'required|integer|min:1',
            'rack_type' => 'required|in:standard,wall-mount,open-frame',
            'description' => 'nullable|string',
        ]);

        $rack = ServerRack::create($validated);

        // Create blank rack units
        for ($i = $rack->total_units; $i >= 1; $i--) {
            RackUnit::create([
                'server_rack_id' => $rack->id,
                'unit_number' => $i,
                'equipment_type' => 'blank',
                'equipment_name' => "Unit $i",
            ]);
        }

        $this->auditLogService->log('create', 'ServerRack', $rack->id, null, $rack->toArray());

        return redirect()->route('server-racks.index')->with('success', 'Server rack created successfully');
    }

    /**
     * Show server rack details.
     */
    public function show(ServerRack $serverRack)
    {
        $this->auditLogService->log('view', 'ServerRack', $serverRack->id);
        return view('server-racks.show', compact('serverRack'));
    }

    /**
     * Edit server rack.
     */
    public function edit(ServerRack $serverRack)
    {
        return view('server-racks.edit', compact('serverRack'));
    }

    /**
     * Update server rack.
     */
    public function update(Request $request, ServerRack $serverRack)
    {
        $validated = $request->validate([
            'rack_name' => 'required|string',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $oldValues = $serverRack->toArray();
        $serverRack->update($validated);

        $this->auditLogService->log('update', 'ServerRack', $serverRack->id, $oldValues, $serverRack->toArray());

        return redirect()->route('server-racks.index')->with('success', 'Server rack updated successfully');
    }

    /**
     * Delete server rack.
     */
    public function destroy(ServerRack $serverRack)
    {
        $oldValues = $serverRack->toArray();
        $serverRack->delete();

        $this->auditLogService->log('delete', 'ServerRack', $serverRack->id, $oldValues, null);

        return redirect()->route('server-racks.index')->with('success', 'Server rack deleted successfully');
    }

    /**
     * Add equipment to a rack unit.
     */
    public function addEquipment(Request $request, ServerRack $serverRack)
    {
        $validated = $request->validate([
            'unit_number' => 'required|integer',
            'unit_height' => 'required|integer|min:1',
            'equipment_name' => 'required|string',
            'equipment_type' => 'required|in:server,switch,router,firewall,pdu,patch_panel,console_server,cable_manager',
            'is_powered' => 'boolean',
            'power_consumption' => 'nullable|string',
        ]);

        // Check availability
        if (!$serverRack->isUnitAvailable($validated['unit_number'], $validated['unit_height'])) {
            return back()->withErrors('Unit space not available');
        }

        // Clear existing units in the space
        $serverRack->rackUnits()
            ->whereBetween('unit_number', [$validated['unit_number'] - $validated['unit_height'] + 1, $validated['unit_number']])
            ->delete();

        // Create the equipment entry
        $unit = RackUnit::create([
            'server_rack_id' => $serverRack->id,
            'unit_number' => $validated['unit_number'],
            'unit_height' => $validated['unit_height'],
            'equipment_name' => $validated['equipment_name'],
            'equipment_type' => $validated['equipment_type'],
            'is_powered' => $validated['is_powered'] ?? true,
            'power_consumption' => $validated['power_consumption'],
        ]);

        $this->auditLogService->log('create', 'RackUnit', $unit->id, null, $unit->toArray());

        return back()->with('success', 'Equipment added successfully');
    }

    /**
     * Update rack unit.
     */
    public function updateUnit(Request $request, RackUnit $rackUnit)
    {
        $validated = $request->validate([
            'equipment_name' => 'required|string',
            'description' => 'nullable|string',
            'is_powered' => 'boolean',
            'power_consumption' => 'nullable|string',
        ]);

        $oldValues = $rackUnit->toArray();
        $rackUnit->update($validated);

        $this->auditLogService->log('update', 'RackUnit', $rackUnit->id, $oldValues, $rackUnit->toArray());

        return back()->with('success', 'Unit updated successfully');
    }

    /**
     * Remove equipment from a rack unit.
     */
    public function removeEquipment(RackUnit $rackUnit)
    {
        $oldValues = $rackUnit->toArray();
        
        $rackUnit->update([
            'equipment_type' => 'blank',
            'equipment_name' => 'Unit ' . $rackUnit->unit_number,
        ]);

        $this->auditLogService->log('update', 'RackUnit', $rackUnit->id, $oldValues, $rackUnit->toArray());

        return back()->with('success', 'Equipment removed successfully');
    }
}
