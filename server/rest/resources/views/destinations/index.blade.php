@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Destinations</h1>
        <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">Add New Destination</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($destinations as $destination)
                    <tr>
                        <td>{{ $destination->id }}</td>
                        <td>
                            <img src="{{ $destination->image }}" alt="{{ $destination->name }}"
                                style="width: 100px; height: 60px; object-fit: cover;" class="img-thumbnail">
                        </td>
                        <td>{{ $destination->name }}</td>
                        <td>${{ number_format($destination->price, 2) }}</td>
                        <td>{{ $destination->duration }} days</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.destinations.show', $destination) }}"
                                    class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.destinations.edit', $destination) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this destination?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection