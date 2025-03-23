<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRole extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the admin routes for destinations.
     *
     * @return void
     */
    public function test_admin_routes_require_authentication()
    {
        // Test si l'utilisateur est redirigé vers la page de login s'il n'est pas authentifié
        $response = $this->get(route('admin.destinations.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test creating a new destination.
     *
     * @return void
     */
    public function test_create_destination()
    {
        // Créer un utilisateur
        $user = User::factory()->create();

        // Se connecter en tant qu'utilisateur
        $this->actingAs($user);

        // Test créer une destination
        $response = $this->post(route('admin.destinations.store'), [
            'name' => 'New Destination',
            'description' => 'A wonderful place to visit.',
            'price' => 1000.00,
            'duration' => 7,
            'image' => null, // Aucun fichier image
        ]);

        // Vérifie si la destination a bien été ajoutée à la base de données
        $this->assertDatabaseHas('destinations', [
            'name' => 'New Destination',
            'description' => 'A wonderful place to visit.',
        ]);

        // Vérifie la redirection
        $response->assertRedirect(route('admin.destinations.index'));
        $response->assertSessionHas('success', 'Destination created successfully');
    }

    /**
     * Test updating a destination.
     *
     * @return void
     */
    public function test_update_destination()
    {
        // Créer un utilisateur
        $user = User::factory()->create();

        // Créer une destination existante
        $destination = Destination::factory()->create();

        // Se connecter en tant qu'utilisateur
        $this->actingAs($user);

        // Test mise à jour d'une destination
        $response = $this->put(route('admin.destinations.update', $destination), [
            'name' => 'Updated Destination',
            'description' => 'A new description.',
            'price' => 1200.00,
            'duration' => 10,
            'image' => null, // Aucun fichier image
        ]);

        // Vérifie si la destination a bien été mise à jour dans la base de données
        $destination->refresh();
        $this->assertEquals('Updated Destination', $destination->name);
        $this->assertEquals('A new description.', $destination->description);

        // Vérifie la redirection
        $response->assertRedirect(route('admin.destinations.index'));
        $response->assertSessionHas('success', 'Destination updated successfully');
    }

    /**
     * Test deleting a destination.
     *
     * @return void
     */
    public function test_delete_destination()
    {
        // Créer un utilisateur
        $user = User::factory()->create();

        // Créer une destination existante
        $destination = Destination::factory()->create();

        // Se connecter en tant qu'utilisateur
        $this->actingAs($user);

        // Test suppression d'une destination
        $response = $this->delete(route('admin.destinations.destroy', $destination));

        // Vérifie si la destination a été supprimée de la base de données
        $this->assertDatabaseMissing('destinations', [
            'id' => $destination->id,
        ]);

        // Vérifie la redirection
        $response->assertRedirect(route('admin.destinations.index'));
        $response->assertSessionHas('success', 'Destination deleted successfully');
    }
}
