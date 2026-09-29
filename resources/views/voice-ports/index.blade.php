@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Voice Ports</h2>
    <a href="{{ route('voice-ports.create') }}" class="btn btn-primary">+ Add Voice Port</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('voice-ports.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search port, PR, PEN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Wall Port</th>
                        <th>User / Department</th>
                        <th>Extension</th>
                        <th>PR Number</th>
                        <th>PEN Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voicePorts as $port)
                    <tr>
                        <td><strong>{{ $port->wall_port_label }}</strong></td>
                        <td>{{ $port->user ? $port->user->full_name : $port->floor_user }}</td>
                        <td>{{ $port->extension_number }}</td>
                        <td><code>{{ $port->pr_number }}</code></td>
                        <td><code>{{ $port->pen_number }}</code></td>
                        <td>
                            <span class="badge bg-{{ $port->port_status === 'active' ? 'success' : ($port->port_status === 'maintenance' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($port->port_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('voice-ports.show', $port->id) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('voice-ports.edit', $port->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No voice ports found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($voicePorts->hasPages())
    <div class="card-footer">
        {{ $voicePorts->links() }}
    </div>
    @endif
</div>
@endsection
