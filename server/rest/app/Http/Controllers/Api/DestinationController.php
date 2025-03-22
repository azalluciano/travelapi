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
        // Récupérer toutes les données validées
        $validatedData = $request->validated();

        // Supprimer l'image des données car on va la traiter séparément
        if (isset($validatedData['image'])) {
            unset($validatedData['image']);
        }

        // Traiter l'image si elle est présente
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filePath = $image->store('images/destinations', 'public');
            $validatedData['image'] = url(Storage::url($filePath));
        } else {
            // Si pas d'image, mettre une valeur par défaut
            $validatedData['image'] = url('/default-image.jpg'); // Adaptez selon vos besoins
        }

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
        // Récupérer toutes les données validées
        $validatedData = $request->validated();

        // Supprimer l'image des données car on va la traiter séparément
        if (isset($validatedData['image'])) {
            unset($validatedData['image']);
        }

        // Traiter l'image si elle est présente dans la requête
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Stocker la nouvelle image dans le dossier public
            $filePath = $image->store('images/destinations', 'public');

            // Créer l'URL complète de l'image avec la fonction url()
            $validatedData['image'] = url(Storage::url($filePath));

            // Supprimer l'ancienne image si elle existe
            if ($destination->image) {
                // Récupérer le chemin de stockage à partir de l'URL
                $oldImageUrl = parse_url($destination->image, PHP_URL_PATH);
                $oldImagePath = 'public' . str_replace('/storage', '', $oldImageUrl);

                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }
        }

        // Mettre à jour la destination avec les nouvelles données
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
