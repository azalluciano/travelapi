<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the destinations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Destination::query();

        // Filter by name if provided
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $destinations = $query->get();

        return response()->json([
            'data' => $destinations
        ]);
    }

    /**
     * Store a newly created destination in storage.
     *
     * @param  \App\Http\Requests\StoreDestinationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDestinationRequest $request)
    {
        $destination = Destination::create($request->validated());

        return response()->json([
            'message' => 'Destination created successfully',
            'data' => $destination
        ], 201);
    }

    /**
     * Display the specified destination.
     *
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Destination $destination)
    {
        return response()->json([
            'data' => $destination
        ]);
    }

    /**
     * Update the specified destination in storage.
     *
     * @param  \App\Http\Requests\UpdateDestinationRequest  $request
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        $destination->update($request->validated());

        return response()->json([
            'message' => 'Destination updated successfully',
            'data' => $destination
        ]);
    }

    /**
     * Remove the specified destination from storage.
     *
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();

        return response()->json([
            'message' => 'Destination deleted successfully'
        ]);
    }
}