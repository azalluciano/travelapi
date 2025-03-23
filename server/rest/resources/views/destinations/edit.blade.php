@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Destination</h1>

        <form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $destination->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    class="form-control">{{ old('description', $destination->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" class="form-control"
                    value="{{ old('price', $destination->price) }}">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="duration">Duration (days)</label>
                <input type="number" id="duration" name="duration" class="form-control"
                    value="{{ old('duration', $destination->duration) }}">
                @error('duration')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="current_image">Current Image</label><br>
                <img src="{{ asset('storage/' . $destination->image) }}" alt="Current Image" width="150"><br><br>
                <input type="hidden" name="current_image" value="{{ $destination->image }}">
            </div>

            <div class="form-group">
                <label for="image">New Image (leave empty to keep current)</label>
                <input type="file" id="image" name="image" class="form-control">
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <p>Accepted formats: jpeg, png, jpg, gif (max 2MB)</p>
            </div>

            <div class="form-group">
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Destination</button>
            </div>
        </form>
    </div>
@endsection