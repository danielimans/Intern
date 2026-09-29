@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add New Voice Port</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('voice-ports.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Wall Port Label *</label>
                        <input type="text" name="wall_port_label" class="form-control" required value="{{ old('wall_port_label') }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Extension Number *</label>
                            <input type="text" name="extension_number" class="form-control" required value="{{ old('extension_number') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PR Number *</label>
                            <input type="text" name="pr_number" class="form-control" required value="{{ old('pr_number') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PEN Number *</label>
                            <input type="text" name="pen_number" class="form-control" required value="{{ old('pen_number') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Floor / User Details *</label>
                        <input type="text" name="floor_user" class="form-control" required value="{{ old('floor_user') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Port Status *</label>
                        <select name="port_status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('voice-ports.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Voice Port</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
