@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Destination Details</h1>
            <div>
                <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ $destination->image }}" alt="{{ $destination->name }}" class="img-fluid rounded">
                </div>
                <div class="col-md-6">
                    <h2>{{ $destination->name }}</h2>
                    <div class="d-flex mb-3">
                        <span class="badge bg-primary me-2">${{ number_format($destination->price, 2) }}</span>
                        <span class="badge bg-info">{{ $destination->duration }} days</span>
                    </div>
                    <h5>Description</h5>
                    <p>{{ $destination->description }}</p>
                    <h5>Created at</h5>
                    <p>{{ $destination->created_at->format('F d, Y') }}</p>
                    <h5>Last updated</h5>
                    <p>{{ $destination->updated_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection