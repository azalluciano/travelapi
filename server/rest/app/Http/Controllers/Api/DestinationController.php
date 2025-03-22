<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

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
     * @param  UpdateDestinationRequest  $request
     * @param  Destination  $destination
     * @return JsonResponse
     */
    public function update(Request $request, Destination $destination)
    {
        // Récupérer les données de base
        $data = $request->except(['image', '_method']);

        // Traitement de l'image (obligatoire)
        if ($request->hasFile('image')) {
            // C'est une nouvelle image
            $image = $request->file('image');
            $filePath = $image->store('images/destinations', 'public');
            $data['image'] = url(Storage::url($filePath));

            // Suppression de l'ancienne image si nécessaire
            if ($destination->image && !str_contains($destination->image, 'default-image.jpg')) {
                $oldImagePath = str_replace(url('/storage/'), '', $destination->image);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
        } else {
            // Pas de nouvelle image, on garde l'ancienne
            $data['image'] = $destination->image;
        }

        // Mise à jour
        $destination->update($data);

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
