@extends('layouts.app')

@section('title', 'Create LAN Port')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-plus"></i> Create LAN Port</h1>
    <p class="text-muted">Add a new LAN port assignment</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('lan-ports.store') }}" class="form">
                    @csrf

                    <div class="mb-3">
                        <label for="wall_port_label" class="form-label">Wall Port Label *</label>
                        <input type="text" class="form-control @error('wall_port_label') is-invalid @enderror" 
                               id="wall_port_label" name="wall_port_label" placeholder="e.g., A1-001" required>
                        @error('wall_port_label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Unique identifier for the physical wall port</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Assign to User</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id">
                                    <option value="">-- Unassigned --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->full_name }} ({{ $user->username }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="extension_number" class="form-label">Extension Number</label>
                                <input type="text" class="form-control @error('extension_number') is-invalid @enderror" 
                                       id="extension_number" name="extension_number" placeholder="e.g., 5001">
                                @error('extension_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" placeholder="user@company.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="switch_port" class="form-label">Switch Port *</label>
                                <input type="text" class="form-control @error('switch_port') is-invalid @enderror" 
                                       id="switch_port" name="switch_port" placeholder="e.g., S1-Gi0/1/48" required>
                                @error('switch_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Physical switch port location</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="floor_user" class="form-label">Floor *</label>
                                <select class="form-select @error('floor_user') is-invalid @enderror" 
                                        id="floor_user" name="floor_user" required>
                                    <option value="">-- Select Floor --</option>
                                    <option value="1" {{ old('floor_user') == '1' ? 'selected' : '' }}>Floor 1</option>
                                    <option value="2" {{ old('floor_user') == '2' ? 'selected' : '' }}>Floor 2</option>
                                    <option value="3" {{ old('floor_user') == '3' ? 'selected' : '' }}>Floor 3</option>
                                    <option value="4" {{ old('floor_user') == '4' ? 'selected' : '' }}>Floor 4</option>
                                </select>
                                @error('floor_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="port_status" class="form-label">Port Status *</label>
                        <select class="form-select @error('port_status') is-invalid @enderror" 
                                id="port_status" name="port_status" required>
                            <option value="active" {{ old('port_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('port_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ old('port_status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('port_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('lan-ports.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Port
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Information
            </div>
            <div class="card-body">
                <h6>Required Fields</h6>
                <ul class="small">
                    <li>Wall Port Label - unique identifier</li>
                    <li>Switch Port - physical location</li>
                    <li>Floor - physical location</li>
                    <li>Port Status - current state</li>
                </ul>

                <hr>

                <h6>Port Status Guide</h6>
                <ul class="small">
                    <li><strong>Active:</strong> In use and operational</li>
                    <li><strong>Inactive:</strong> Not in use</li>
                    <li><strong>Maintenance:</strong> Temporarily disabled</li>
                </ul>

                <hr>

                <h6>Best Practices</h6>
                <ul class="small">
                    <li>Use consistent naming conventions</li>
                    <li>Assign to users when activated</li>
                    <li>Add descriptive notes for future reference</li>
                    <li>Update status regularly</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
