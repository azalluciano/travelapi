<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test retrieving a list of destinations.
     *
     * @return void
     */
    public function test_get_all_destinations()
    {
        // Créer des destinations
        $destinations = Destination::factory()->count(5)->create();

        // Effectuer la requête GET pour récupérer la liste des destinations
        $response = $this->getJson(route('destinations.index'));

        // Vérifier la structure de la réponse
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'duration',
                    'image',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        // Vérifier que les destinations sont présentes dans la réponse
        $response->assertJsonCount(5, 'data');
    }

    /**
     * Test retrieving a specific destination.
     *
     * @return void
     */
    public function test_get_single_destination()
    {
        // Créer une destination
        $destination = Destination::factory()->create();

        // Effectuer la requête GET pour récupérer une destination spécifique
        $response = $this->getJson(route('destinations.show', $destination));

        // Vérifier la structure de la réponse
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'price',
                'duration',
                'image',
                'created_at',
                'updated_at',
            ],
        ]);

        // Vérifier que les informations de la destination sont correctes
        $response->assertJsonFragment([
            'name' => $destination->name,
            'description' => $destination->description,
        ]);
    }

    /**
     * Test searching destinations by name.
     *
     * @return void
     */
    public function test_search_destinations_by_name()
    {
        // Créer une destination
        $destination = Destination::factory()->create(['name' => 'Paris']);

        // Effectuer la requête GET avec un paramètre de recherche
        $response = $this->getJson(route('destinations.index') . '?name=Paris');

        // Vérifier la réponse
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Paris']);
    }
}
