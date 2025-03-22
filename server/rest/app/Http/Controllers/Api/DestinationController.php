<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        // Récupérer les données validées sans l'image
        $validatedData = $request->except('image');

        // Traiter l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Stocker l'image
            $image->storeAs('public/images/destinations', $imageName);

            // Créer l'URL complète avec localhost
            $validatedData['image'] = url('/storage/images/destinations/' . $imageName);
        }

        // Créer la destination
        $destination = Destination::create($validatedData);

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
        // Récupérer les données validées sans l'image
        $validatedData = $request->except('image');

        // Traiter l'image si elle est présente dans la requête
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Stocker la nouvelle image
            $image->storeAs('public/images/destinations', $imageName);

            // Créer l'URL complète avec le domaine actuel
            $validatedData['image'] = url('/storage/images/destinations/' . $imageName);

            // Supprimer l'ancienne image si elle existe
            // Récupérer juste le nom du fichier depuis l'URL
            if ($destination->image) {
                $oldImagePath = str_replace(url('/storage'), 'public', parse_url($destination->image, PHP_URL_PATH));
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }
        }

        // Mettre à jour la destination
        $destination->update($validatedData);

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
