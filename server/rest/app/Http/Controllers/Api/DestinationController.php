<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    /**
     * Display a listing of the destinations.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Destination::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $destinations = $query->get()->toArray();

        return response()->json([
            'data' => $destinations
        ]);
    }

    /**
     * Store a newly created destination in storage.
     *
     * @param StoreDestinationRequest $request
     * @return JsonResponse
     */
    public function store(StoreDestinationRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // Remove image from data as we'll handle it separately
        if (isset($validatedData['image'])) {
            unset($validatedData['image']);
        }

        $validatedData['image'] = $this->handleImageUpload($request);

        $destination = Destination::create($validatedData);

        return response()->json([
            'message' => 'Destination created successfully',
            'data' => $destination
        ], 201);
    }

    /**
     * Display the specified destination.
     *
     * @param Destination $destination
     * @return JsonResponse
     */
    public function show(Destination $destination): JsonResponse
    {
        return response()->json([
            'data' => $destination
        ]);
    }

    /**
     * Update the specified destination in storage.
     *
     * @param Request $request
     * @param Destination $destination
     * @return JsonResponse
     */
    public function update(Request $request, Destination $destination): JsonResponse
    {
        $data = $request->except(['image', '_method']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request, $destination);
        } else {
            $data['image'] = $destination->image;
        }

        $destination->update($data);

        return response()->json([
            'message' => 'Destination updated successfully',
            'data' => $destination
        ]);
    }

    /**
     * Remove the specified destination from storage.
     *
     * @param Destination $destination
     * @return JsonResponse
     */
    public function destroy(Destination $destination): JsonResponse
    {
        $destination->delete();

        return response()->json([
            'message' => 'Destination deleted successfully'
        ]);
    }

    /**
     * Handle image upload and storage.
     *
     * @param Request $request
     * @param Destination|null $destination
     * @return string
     */
    private function handleImageUpload(Request $request, ?Destination $destination = null): string
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filePath = $image->store('images/destinations', 'public');

            // Delete old image if updating an existing destination
            if ($destination && $destination->image && !str_contains($destination->image, 'default-image.jpg')) {
                $this->deleteOldImage($destination->image);
            }

            return url(Storage::url($filePath));
        }

        // If no image uploaded, use default or keep existing
        return $destination ? $destination->image : url('/default-image.jpg');
    }

    /**
     * Delete old image from storage.
     *
     * @param string $imageUrl
     * @return void
     */
    private function deleteOldImage(string $imageUrl): void
    {
        $oldImagePath = str_replace(url('/storage/'), '', $imageUrl);
        if (Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }
    }
}
