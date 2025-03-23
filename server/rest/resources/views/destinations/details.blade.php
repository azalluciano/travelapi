@extends('layouts.app')

@section('content')
    <div class="row mb-5">
        <div class="col-md-6">
            <img src="{{ $destination->image }}" class="img-fluid rounded" alt="{{ $destination->name }}">
        </div>
        <div class="col-md-6">
            <h1>{{ $destination->name }}</h1>
            <div class="d-flex mb-3">
                <span class="badge bg-primary me-2">${{ number_format($destination->price, 2) }}</span>
                <span class="badge bg-info">{{ $destination->duration }} days</span>
            </div>
            <h5>Description</h5>
            <p>{{ $destination->description }}</p>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Back to Destinations</a>
        </div>
    </div>
@endsection