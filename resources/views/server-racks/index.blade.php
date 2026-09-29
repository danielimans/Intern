@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Server Racks</h2>
    <a href="{{ route('server-racks.create') }}" class="btn btn-primary">+ Add Server Rack</a>
</div>

<div class="row">
    @forelse($serverRacks as $rack)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $rack->rack_name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $rack->location }}</h6>
                <p class="card-text">
                    <strong>Total Units:</strong> {{ $rack->total_units }}U<br>
                    <strong>Type:</strong> {{ ucfirst($rack->rack_type) }}<br>
                    <strong>Description:</strong> {{ $rack->description ?? 'N/A' }}
                </p>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('server-racks.visualize', $rack->id) }}" class="btn btn-sm btn-info text-white">3D View</a>
                    <a href="{{ route('server-racks.show', $rack->id) }}" class="btn btn-sm btn-secondary">Details</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">No server racks found. <a href="{{ route('server-racks.create') }}">Create one now</a>.</div>
    </div>
    @endforelse
</div>
@endsection
