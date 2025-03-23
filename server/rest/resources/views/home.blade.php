@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="px-4 py-5 my-5 text-center">
        <h1 class="display-5 fw-bold">Find Your Perfect Honeymoon Destination</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">Discover amazing destinations for your once-in-a-lifetime honeymoon experience.</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        @foreach($destinations as $destination)
            <div class="col">
                <div class="card h-100 destination-card">
                    <img src="{{ $destination->image }}" class="card-img-top" alt="{{ $destination->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $destination->name }}</h5>
                        <p class="card-text">{{ Str::limit($destination->description, 150) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary">${{ number_format($destination->price, 2) }}</span>
                                <span class="badge bg-info">{{ $destination->duration }} days</span>
                            </div>
                            <a href="{{ route('destinations.details', $destination) }}"
                                class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection