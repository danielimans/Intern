@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit LAN Port: {{ $lanPort->wall_port_label }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('lan-ports.update', $lanPort->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Wall Port Label *</label>
                        <input type="text" name="wall_port_label" class="form-control" required value="{{ old('wall_port_label', $lanPort->wall_port_label) }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Switch Port *</label>
                            <input type="text" name="switch_port" class="form-control" required value="{{ old('switch_port', $lanPort->switch_port) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Extension Number</label>
                            <input type="text" name="extension_number" class="form-control" value="{{ old('extension_number', $lanPort->extension_number) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $lanPort->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Floor / User Details *</label>
                        <input type="text" name="floor_user" class="form-control" required value="{{ old('floor_user', $lanPort->floor_user) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Port Status *</label>
                        <select name="port_status" class="form-select" required>
                            <option value="active" {{ $lanPort->port_status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $lanPort->port_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ $lanPort->port_status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $lanPort->notes) }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('lan-ports.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update LAN Port</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
