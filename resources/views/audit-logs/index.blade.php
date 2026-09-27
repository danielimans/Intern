@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-list"></i> Audit Logs</h1>
        <p class="text-muted">View all system changes and activities (30-day retention)</p>
    </div>
    <div>
        <a href="{{ route('audit-logs.export') }}" class="btn btn-outline-primary">
            <i class="fas fa-download"></i> Export CSV
        </a>
        <a href="{{ route('audit-logs.statistics') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-bar"></i> Statistics
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-3">
            <div class="col-md-2">
                <label for="action" class="form-label">Action</label>
                <select name="action" id="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                    <option value="export" {{ request('action') == 'export' ? 'selected' : '' }}>Export</option>
                    <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>View</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="model_type" class="form-label">Type</label>
                <select name="model_type" id="model_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="LanPort" {{ request('model_type') == 'LanPort' ? 'selected' : '' }}>LAN Port</option>
                    <option value="VoicePort" {{ request('model_type') == 'VoicePort' ? 'selected' : '' }}>Voice Port</option>
                    <option value="ServerRack" {{ request('model_type') == 'ServerRack' ? 'selected' : '' }}>Server Rack</option>
                    <option value="RackUnit" {{ request('model_type') == 'RackUnit' ? 'selected' : '' }}>Rack Unit</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Audit Logs Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Model ID</th>
                        <th>IP Address</th>
                        <th>Description</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                        <tr>
                            <td>
                                <small class="text-muted">{{ $log->created_at->format('M d, Y H:i:s') }}</small>
                            </td>
                            <td>
                                <strong>{{ $log->user?->full_name ?? 'System' }}</strong>
                                <br>
                                <small class="text-muted">@{{ $log->user?->username }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $log->getActionColor() }}">
                                    {{ $log->getActionLabel() }}
                                </span>
                            </td>
                            <td>
                                <small class="text-secondary">{{ $log->model_type }}</small>
                            </td>
                            <td>
                                {{ $log->model_id ?? 'N/A' }}
                            </td>
                            <td>
                                <small class="text-muted" title="{{ $log->user_agent }}">{{ $log->ip_address }}</small>
                            </td>
                            <td>
                                {{ $log->description ?? '-' }}
                            </td>
                            <td>
                                @if($log->old_values || $log->new_values)
                                    <a href="{{ route('audit-logs.show', $log) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No audit logs found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div>{{ $auditLogs->count() }} of {{ $auditLogs->total() }} logs</div>
        <div>
            {{ $auditLogs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Retention Notice -->
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle"></i> 
    <strong>Note:</strong> Audit logs are automatically retained for 30 days and then purged. 
    For compliance archival, export logs regularly.
</div>

@endsection
