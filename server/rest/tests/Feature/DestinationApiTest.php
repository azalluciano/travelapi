<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class DestinationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_destinations(): void
    {
        Destination::factory()->count(3)->create();

        $response = $this->getJson('/api/destinations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'duration', 'image', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_filter_destinations_by_name(): void
    {
        Destination::factory()->create(['name' => 'Paris']);
        Destination::factory()->create(['name' => 'London']);
        Destination::factory()->create(['name' => 'New York']);

        $response = $this->getJson('/api/destinations?name=Paris');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Paris');
    }

    public function test_can_show_destination(): void
    {
        $destination = Destination::factory()->create();

        $response = $this->getJson('/api/destinations/' . $destination->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $destination->id)
            ->assertJsonPath('data.name', $destination->name);
    }

    public function test_admin_can_create_destination(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $destinationData = [
            'name' => 'Test Destination',
            'description' => 'Test Description',
            'price' => 199.99,
            'duration' => 7,
            'image' => UploadedFile::fake()->create(
                'test-image.jpg',
                100,
                'image/jpeg'
            )
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/destinations', $destinationData);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', $destinationData['name']);

        $this->assertDatabaseHas('destinations', [
            'name' => $destinationData['name']
        ]);
    }

    public function test_non_admin_cannot_create_destination(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $destinationData = [
            'name' => 'Test Destination',
            'description' => 'Test Description',
            'price' => 199.99,
            'duration' => 7,
            'image' => UploadedFile::fake()->create('test-image.jpg', 100, 'image/jpeg')
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/destinations', $destinationData);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_destination(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $destination = Destination::factory()->create();

        $updateData = [
            'name' => 'Updated Destination',
            'price' => 299.99
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/destinations/' . $destination->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $updateData['name'])
            ->assertJsonPath('data.price', (string)$updateData['price']);

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'name' => $updateData['name'],
            'price' => $updateData['price']
        ]);
    }

    public function test_admin_can_delete_destination(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $destination = Destination::factory()->create();

        $response = $this->actingAs($admin)
            ->deleteJson('/api/destinations/' . $destination->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('destinations', [
            'id' => $destination->id
        ]);
    }
}
