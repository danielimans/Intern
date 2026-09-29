@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Voice Port Details: {{ $voicePort->wall_port_label }}</h5>
                <a href="{{ route('voice-ports.edit', $voicePort->id) }}" class="btn btn-sm btn-light">Edit</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Wall Port Label</th><td>{{ $voicePort->wall_port_label }}</td></tr>
                    <tr><th>Extension Number</th><td>{{ $voicePort->extension_number }}</td></tr>
                    <tr><th>User / Floor</th><td>{{ $voicePort->user ? $voicePort->user->full_name : $voicePort->floor_user }}</td></tr>
                    <tr><th>Email</th><td>{{ $voicePort->email ?? 'N/A' }}</td></tr>
                    <tr><th>PR Number</th><td><code>{{ $voicePort->pr_number }}</code></td></tr>
                    <tr><th>PEN Number</th><td><code>{{ $voicePort->pen_number }}</code></td></tr>
                    <tr><th>Port Status</th><td><span class="badge bg-success">{{ ucfirst($voicePort->port_status) }}</span></td></tr>
                    <tr><th>Notes</th><td>{{ $voicePort->notes ?? 'None' }}</td></tr>
                    <tr><th>Created At</th><td>{{ $voicePort->created_at }}</td></tr>
                </table>
                <a href="{{ route('voice-ports.index') }}" class="btn btn-secondary">&laquo; Back to Listing</a>
            </div>
        </div>
    </div>
</div>
@endsection
