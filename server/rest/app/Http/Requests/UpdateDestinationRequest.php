<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        $isAuthenticated = Auth::check();
        $isAdmin = $user?->is_admin ?? false; // Utilisation de l'opérateur null-safe `?->`

        // Log uniquement en mode debug
        if (config('app.debug')) {
            Log::info('Autorisation de mise à jour:', [
                'authenticated' => $isAuthenticated,
                'user_id' => $user?->id ?? 'Non connecté',
                'is_admin' => $isAdmin
            ]);
        }

        return $isAuthenticated && $isAdmin;
    }

    public function rules(): array
    {
        // Log uniquement en mode debug
        if (config('app.debug')) {
            Log::info('Données reçues dans UpdateDestinationRequest:', $this->all());
        }

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_image' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'duration.required' => 'La durée est obligatoire.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être de type: jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'current_image.url' => 'L\'URL de l\'image existante est invalide.',
        ];
    }

    protected function failedAuthorization()
    {
        if (config('app.debug')) {
            Log::warning('Échec d\'autorisation pour la mise à jour de destination');
        }

        throw new AuthorizationException('Vous n\'êtes pas autorisé à mettre à jour cette destination.');
    }
}
