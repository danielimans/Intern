@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Server Rack: {{ $serverRack->rack_name }}</h5>
                <div>
                    <a href="{{ route('server-racks.visualize', $serverRack->id) }}" class="btn btn-sm btn-info text-white me-1">3D View</a>
                    <a href="{{ route('server-racks.edit', $serverRack->id) }}" class="btn btn-sm btn-light">Edit</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Rack Name</th><td>{{ $serverRack->rack_name }}</td></tr>
                    <tr><th>Location</th><td>{{ $serverRack->location }}</td></tr>
                    <tr><th>Total Units</th><td>{{ $serverRack->total_units }}U</td></tr>
                    <tr><th>Rack Type</th><td>{{ ucfirst(str_replace('-', ' ', $serverRack->rack_type)) }}</td></tr>
                    <tr><th>Description</th><td>{{ $serverRack->description ?? 'N/A' }}</td></tr>
                    <tr><th>Created At</th><td>{{ $serverRack->created_at }}</td></tr>
                </table>

                <h6 class="mt-4">Rack Units</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr><th>Unit #</th><th>Equipment</th><th>Type</th><th>Description</th></tr>
                        </thead>
                        <tbody>
                            @foreach($serverRack->rackUnits->sortBy('unit_number') as $unit)
                            <tr>
                                <td><strong>U{{ $unit->unit_number }}</strong></td>
                                <td>{{ $unit->equipment_name }}</td>
                                <td>{{ ucfirst($unit->equipment_type) }}</td>
                                <td>{{ $unit->description ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('server-racks.index') }}" class="btn btn-secondary">&laquo; Back to Listing</a>
            </div>
        </div>
    </div>
</div>
@endsection
