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
     * Test listing destinations.
     */
    public function test_can_list_destinations(): void
    {
        // Create test destinations
        Destination::factory()->count(3)->create();

        // Make request to API
        $response = $this->getJson('/api/destinations');

        // Assert success and correct data structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'duration', 'image', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Test filtering destinations by name.
     */
    public function test_can_filter_destinations_by_name(): void
    {
        // Create test destinations
        Destination::factory()->create(['name' => 'Paris']);
        Destination::factory()->create(['name' => 'London']);
        Destination::factory()->create(['name' => 'New York']);

        // Make request to API with filter
        $response = $this->getJson('/api/destinations?name=Paris');

        // Assert success and correct filtered data
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Paris');
    }

    /**
     * Test showing a specific destination.
     */
    public function test_can_show_destination(): void
    {
        // Create test destination
        $destination = Destination::factory()->create();

        // Make request to API
        $response = $this->getJson('/api/destinations/' . $destination->id);

        // Assert success and correct data
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $destination->id)
            ->assertJsonPath('data.name', $destination->name);
    }

    /**
     * Test creating a destination as admin.
     */
    public function test_admin_can_create_destination(): void
    {
        // Create admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Prepare test data
        $destinationData = [
            'name' => 'Test Destination',
            'description' => 'Test Description',
            'price' => 199.99,
            'duration' => 7,
            'image' => 'test-image.jpg'
        ];

        // Make authenticated request to API
        $response = $this->actingAs($admin)
            ->postJson('/api/destinations', $destinationData);

        // Assert success and correct data
        $response->assertStatus(201)
            ->assertJsonPath('data.name', $destinationData['name']);

        // Assert data was saved to database
        $this->assertDatabaseHas('destinations', [
            'name' => $destinationData['name']
        ]);
    }

    /**
     * Test non-admin cannot create a destination.
     */
    public function test_non_admin_cannot_create_destination(): void
    {
        // Create regular user
        $user = User::factory()->create(['is_admin' => false]);

        // Prepare test data
        $destinationData = [
            'name' => 'Test Destination',
            'description' => 'Test Description',
            'price' => 199.99,
            'duration' => 7,
            'image' => 'test-image.jpg'
        ];

        // Make authenticated request to API
        $response = $this->actingAs($user)
            ->postJson('/api/destinations', $destinationData);

        // Assert forbidden
        $response->assertStatus(403);
    }

    /**
     * Test updating a destination as admin.
     */
    public function test_admin_can_update_destination(): void
    {
        // Create admin user and destination
        $admin = User::factory()->create(['is_admin' => true]);
        $destination = Destination::factory()->create();

        // Prepare update data
        $updateData = [
            'name' => 'Updated Destination',
            'price' => 299.99
        ];

        // Make authenticated request to API
        $response = $this->actingAs($admin)
            ->putJson('/api/destinations/' . $destination->id, $updateData);

        // Assert success and correct data
        $response->assertStatus(200)
            ->assertJsonPath('data.name', $updateData['name'])
            ->assertJsonPath('data.price', $updateData['price']);

        // Assert data was updated in database
        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'name' => $updateData['name'],
            'price' => $updateData['price']
        ]);
    }

    /**
     * Test deleting a destination as admin.
     */
    public function test_admin_can_delete_destination(): void
    {
        // Create admin user and destination
        $admin = User::factory()->create(['is_admin' => true]);
        $destination = Destination::factory()->create();

        // Make authenticated request to API
        $response = $this->actingAs($admin)
            ->deleteJson('/api/destinations/' . $destination->id);

        // Assert success
        $response->assertStatus(200);

        // Assert data was deleted from database
        $this->assertDatabaseMissing('destinations', [
            'id' => $destination->id
        ]);
    }
}