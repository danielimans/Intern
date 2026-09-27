@extends('layouts.app')

@section('title', $rack->rack_name . ' - 360° Visualization')

@section('extra-css')
<style>
    .visualizer-container {
        perspective: 1000px;
        width: 100%;
        height: 600px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        margin-bottom: 2rem;
    }

    .rack-3d {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
    }

    .rack-unit {
        position: absolute;
        width: 80%;
        left: 10%;
        border: 2px solid #333;
        border-radius: 4px;
        padding: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .rack-unit:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 100;
    }

    .rack-unit.blank {
        background-color: #ecf0f1;
        color: #7f8c8d;
        border-color: #bdc3c7;
    }

    .rack-unit.server {
        background-color: #4CAF50;
        color: white;
    }

    .rack-unit.switch {
        background-color: #2196F3;
        color: white;
    }

    .rack-unit.router {
        background-color: #FF9800;
        color: white;
    }

    .rack-unit.firewall {
        background-color: #F44336;
        color: white;
    }

    .rack-unit.pdu {
        background-color: #9C27B0;
        color: white;
    }

    .rack-unit.patch_panel {
        background-color: #00BCD4;
        color: white;
    }

    .rack-unit.console_server {
        background-color: #795548;
        color: white;
    }

    .rack-unit.cable_manager {
        background-color: #999;
        color: white;
    }

    .rack-label {
        font-size: 10px;
        opacity: 0.8;
    }

    .rack-status {
        font-size: 10px;
        padding: 2px 4px;
        background: rgba(0,0,0,0.2);
        border-radius: 2px;
    }

    .controls {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 5px;
        z-index: 50;
    }

    .control-btn {
        width: 40px;
        height: 40px;
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .control-btn:hover {
        background: #667eea;
        color: white;
    }

    .unit-detail {
        background: white;
        padding: 12px;
        border-radius: 4px;
        font-size: 13px;
        margin-top: 8px;
    }

    .unit-detail .label {
        font-weight: 600;
        color: #333;
    }

    .unit-detail .value {
        color: #666;
        margin-left: 8px;
    }

    .legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-color {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: 2px solid #333;
    }
</style>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-cube"></i> 360° Server Rack Visualization</h1>
        <p class="text-muted">{{ $rack->rack_name }} - {{ $rack->location }}</p>
    </div>
    <div>
        <a href="{{ route('server-racks.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('server-racks.edit', $rack) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Rack
        </a>
    </div>
</div>

<!-- Rack Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $rack->total_units }}</h3>
                <p class="text-muted mb-0">Total Units</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $rack->occupied_count }}</h3>
                <p class="text-muted mb-0">Occupied Units</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $rack->utilization_percentage }}%</h3>
                <p class="text-muted mb-0">Utilization</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $rack->total_units - $rack->occupied_count }}</h3>
                <p class="text-muted mb-0">Available Units</p>
            </div>
        </div>
    </div>
</div>

<!-- 360 Visualization -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-rotate"></i> 360° View - Click Units for Details
    </div>
    <div class="card-body">
        <div class="visualizer-container" id="visualizer">
            <div class="rack-3d" id="rack3d"></div>
            <div class="controls">
                <button class="control-btn" onclick="rotateLeft()" title="Rotate Left">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="control-btn" onclick="rotateRight()" title="Rotate Right">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button class="control-btn" onclick="zoomIn()" title="Zoom In">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="control-btn" onclick="zoomOut()" title="Zoom Out">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="control-btn" onclick="resetView()" title="Reset">
                    <i class="fas fa-redo"></i>
                </button>
            </div>
        </div>

        <!-- Selected Unit Details -->
        <div id="unitDetails" class="unit-detail" style="display: none;">
            <div class="label">Selected Unit Details:</div>
            <div id="detailsContent"></div>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background: #4CAF50; border-color: #333;"></div>
                <span>Server</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #2196F3; border-color: #333;"></div>
                <span>Switch</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #FF9800; border-color: #333;"></div>
                <span>Router</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #F44336; border-color: #333;"></div>
                <span>Firewall</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #9C27B0; border-color: #333;"></div>
                <span>PDU</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #00BCD4; border-color: #333;"></div>
                <span>Patch Panel</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ecf0f1; border-color: #bdc3c7;"></div>
                <span>Blank</span>
            </div>
        </div>
    </div>
</div>

<!-- Equipment List -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-list"></i> Rack Units
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Unit #</th>
                        <th>Equipment Type</th>
                        <th>Equipment Name</th>
                        <th>Powered</th>
                        <th>Power (W)</th>
                        <th>Ports</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rack->rackUnits()->orderByDesc('unit_number')->get() as $unit)
                        <tr>
                            <td><strong>U{{ $unit->unit_number }}</strong></td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $unit->equipment_type)) }}</span>
                            </td>
                            <td>{{ $unit->equipment_name }}</td>
                            <td>
                                @if($unit->is_powered)
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="fas fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>{{ $unit->power_consumption ?? '-' }}</td>
                            <td>{{ count($unit->getConnections()) }} ports</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#editUnitModal" 
                                            onclick="editUnit({{ $unit->id }}, '{{ $unit->equipment_name }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('server-racks.remove-equipment', $unit) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Remove this equipment?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No equipment in this rack</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    let rotationY = 0;
    let rotationX = 0;
    let zoomLevel = 1;
    const vizData = {!! $vizData !!};

    function initializeVisualization() {
        const rack3d = document.getElementById('rack3d');
        rack3d.innerHTML = '';

        vizData.units.forEach((unit, index) => {
            const unitHeight = 40; // pixels per U
            const topPosition = (vizData.total_units - unit.unit_number) * unitHeight + 20;
            
            const unitEl = document.createElement('div');
            unitEl.className = `rack-unit ${unit.equipment_type}`;
            unitEl.style.top = topPosition + 'px';
            unitEl.style.height = unitHeight - 2 + 'px';
            unitEl.innerHTML = `
                <span class="rack-label">U${unit.unit_number}</span>
                <span>${unit.equipment_name.substring(0, 15)}</span>
                <span class="rack-status">${unit.is_powered ? 'PWR' : 'OFF'}</span>
            `;
            
            unitEl.onclick = () => showUnitDetails(unit);
            rack3d.appendChild(unitEl);
        });

        updateVisualizerTransform();
    }

    function updateVisualizerTransform() {
        const rack3d = document.getElementById('rack3d');
        rack3d.style.transform = `rotateX(${rotationX}deg) rotateY(${rotationY}deg) scale(${zoomLevel})`;
    }

    function rotateLeft() {
        rotationY -= 15;
        updateVisualizerTransform();
    }

    function rotateRight() {
        rotationY += 15;
        updateVisualizerTransform();
    }

    function zoomIn() {
        zoomLevel += 0.1;
        updateVisualizerTransform();
    }

    function zoomOut() {
        if (zoomLevel > 0.5) {
            zoomLevel -= 0.1;
            updateVisualizerTransform();
        }
    }

    function resetView() {
        rotationY = 0;
        rotationX = 0;
        zoomLevel = 1;
        updateVisualizerTransform();
        document.getElementById('unitDetails').style.display = 'none';
    }

    function showUnitDetails(unit) {
        const detailsDiv = document.getElementById('unitDetails');
        const contentDiv = document.getElementById('detailsContent');
        
        let html = `
            <div class="label">U${unit.unit_number} - ${unit.equipment_name}</div>
            <div class="unit-detail">
                <div><span class="label">Type:</span><span class="value">${unit.equipment_type}</span></div>
                <div><span class="label">Status:</span><span class="value">${unit.is_powered ? 'Powered' : 'Off'}</span></div>
            </div>
        `;
        
        contentDiv.innerHTML = html;
        detailsDiv.style.display = 'block';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initializeVisualization);
</script>
@endsection
