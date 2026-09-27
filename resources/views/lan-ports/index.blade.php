@extends('layouts.app')

@section('title', 'LAN Ports')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-ethernet"></i> LAN Ports Management</h1>
        <p class="text-muted">Manage and track all LAN port assignments</p>
    </div>
    <div>
        <a href="{{ route('lan-ports.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New LAN Port
        </a>
        <a href="{{ route('lan-ports.export') }}" class="btn btn-outline-primary">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('lan-ports.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="Wall port, email, switch port..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="floor" class="form-label">Floor</label>
                <select name="floor" id="floor" class="form-select">
                    <option value="">All Floors</option>
                    <option value="1" {{ request('floor') == '1' ? 'selected' : '' }}>Floor 1</option>
                    <option value="2" {{ request('floor') == '2' ? 'selected' : '' }}>Floor 2</option>
                    <option value="3" {{ request('floor') == '3' ? 'selected' : '' }}>Floor 3</option>
                    <option value="4" {{ request('floor') == '4' ? 'selected' : '' }}>Floor 4</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ports Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Wall Port Label</th>
                        <th>User</th>
                        <th>Extension</th>
                        <th>Email</th>
                        <th>Switch Port</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lanPorts as $port)
                        <tr>
                            <td>
                                <strong>{{ $port->wall_port_label }}</strong>
                            </td>
                            <td>
                                {{ $port->user?->full_name ?? 'Unassigned' }}
                            </td>
                            <td>
                                <small>{{ $port->extension_number ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $port->email ?? '-' }}</small>
                            </td>
                            <td>
                                <code>{{ $port->switch_port }}</code>
                            </td>
                            <td>
                                {{ $port->floor_user }}
                            </td>
                            <td>
                                <span class="badge badge-status badge-{{ $port->getStatusColor() }}">
                                    {{ $port->getStatusBadge() }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $port->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('lan-ports.show', $port) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lan-ports.edit', $port) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('lan-ports.destroy', $port) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No LAN ports found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $lanPorts->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
