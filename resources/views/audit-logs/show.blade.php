@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Audit Log #{{ $auditLog->id }}</h5>
                <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-light">&laquo; Back</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $auditLog->id }}</td></tr>
                    <tr><th>Action</th><td><span class="badge bg-primary">{{ strtoupper($auditLog->action) }}</span></td></tr>
                    <tr><th>Model</th><td>{{ $auditLog->model_type }}</td></tr>
                    <tr><th>Model ID</th><td>{{ $auditLog->model_id ?? 'N/A' }}</td></tr>
                    <tr><th>User</th><td>{{ $auditLog->user ? $auditLog->user->full_name : 'System' }}</td></tr>
                    <tr><th>IP Address</th><td>{{ $auditLog->ip_address ?? 'N/A' }}</td></tr>
                    <tr><th>Timestamp</th><td>{{ $auditLog->created_at }}</td></tr>
                    @if($auditLog->old_values)
                    <tr>
                        <th>Old Values</th>
                        <td><pre class="bg-light p-2 rounded small mb-0">{{ json_encode(json_decode($auditLog->old_values), JSON_PRETTY_PRINT) }}</pre></td>
                    </tr>
                    @endif
                    @if($auditLog->new_values)
                    <tr>
                        <th>New Values</th>
                        <td><pre class="bg-light p-2 rounded small mb-0">{{ json_encode(json_decode($auditLog->new_values), JSON_PRETTY_PRINT) }}</pre></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
