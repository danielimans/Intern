@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <p class="text-muted">Welcome to Network Infrastructure Management System</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-primary">
            <span class="stat-number">{{ $stats['total_lan_ports'] }}</span>
            <span class="stat-label">Total LAN Ports</span>
            <small class="d-block mt-2">
                <i class="fas fa-circle" style="color: #28a745;"></i> {{ $stats['active_lan_ports'] }} Active
            </small>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-success">
            <span class="stat-number">{{ $stats['total_voice_ports'] }}</span>
            <span class="stat-label">Total Voice Ports</span>
            <small class="d-block mt-2">
                <i class="fas fa-circle" style="color: #28a745;"></i> {{ $stats['active_voice_ports'] }} Active
            </small>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-info">
            <span class="stat-number">{{ $stats['total_server_racks'] }}</span>
            <span class="stat-label">Server Racks</span>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-warning">
            <span class="stat-number">200</span>
            <span class="stat-label">Total Users</span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Port Status Breakdown -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie"></i> Port Status Breakdown
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-secondary mb-3">LAN Ports</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-success">Active</span></span>
                        <strong>{{ $portStatusBreakdown['lan']['active'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-secondary">Inactive</span></span>
                        <strong>{{ $portStatusBreakdown['lan']['inactive'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span><span class="badge badge-warning">Maintenance</span></span>
                        <strong>{{ $portStatusBreakdown['lan']['maintenance'] }}</strong>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-secondary mb-3">Voice Ports</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-success">Active</span></span>
                        <strong>{{ $portStatusBreakdown['voice']['active'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-secondary">Inactive</span></span>
                        <strong>{{ $portStatusBreakdown['voice']['inactive'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><span class="badge badge-warning">Maintenance</span></span>
                        <strong>{{ $portStatusBreakdown['voice']['maintenance'] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LAN Ports by Floor -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building"></i> LAN Ports by Floor
            </div>
            <div class="card-body">
                @forelse($lanPortsByFloor as $floor)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e0e0e0;">
                        <span class="fw-500">{{ $floor->floor_user }}</span>
                        <span class="badge bg-primary">{{ $floor->count }} Ports</span>
                    </div>
                @empty
                    <p class="text-muted">No LAN ports available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-history"></i> Recent Activity (Last 7 Days)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivity as $activity)
                        <tr>
                            <td>
                                <small class="text-muted">{{ $activity->created_at->format('M d, Y H:i') }}</small>
                            </td>
                            <td>{{ $activity->user?->full_name ?? 'System' }}</td>
                            <td>
                                <span class="badge bg-{{ $activity->getActionColor() }}">
                                    {{ $activity->getActionLabel() }}
                                </span>
                            </td>
                            <td>
                                <small class="text-secondary">{{ $activity->model_type }}</small>
                            </td>
                            <td>
                                @if($activity->model_id)
                                    <small class="text-muted">ID: {{ $activity->model_id }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No recent activity
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="/audit-logs" class="btn btn-sm btn-outline-primary" style="display: {{ auth()->user()->isAdmin() ? 'inline-block' : 'none' }};">
            <i class="fas fa-list"></i> View Full Audit Logs
        </a>
    </div>
</div>

@endsection
