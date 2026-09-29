@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">LAN Port Details: {{ $lanPort->wall_port_label }}</h5>
                <a href="{{ route('lan-ports.edit', $lanPort->id) }}" class="btn btn-sm btn-light">Edit</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Wall Port Label</th><td>{{ $lanPort->wall_port_label }}</td></tr>
                    <tr><th>Switch Port</th><td>{{ $lanPort->switch_port }}</td></tr>
                    <tr><th>Extension Number</th><td>{{ $lanPort->extension_number ?? 'N/A' }}</td></tr>
                    <tr><th>User / Floor</th><td>{{ $lanPort->user ? $lanPort->user->full_name : $lanPort->floor_user }}</td></tr>
                    <tr><th>Email</th><td>{{ $lanPort->email ?? 'N/A' }}</td></tr>
                    <tr><th>Port Status</th><td><span class="badge bg-success">{{ ucfirst($lanPort->port_status) }}</span></td></tr>
                    <tr><th>Notes</th><td>{{ $lanPort->notes ?? 'None' }}</td></tr>
                    <tr><th>Created At</th><td>{{ $lanPort->created_at }}</td></tr>
                </table>
                <a href="{{ route('lan-ports.index') }}" class="btn btn-secondary">&laquo; Back to Listing</a>
            </div>
        </div>
    </div>
</div>
@endsection
