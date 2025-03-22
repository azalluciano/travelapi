<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin registration.
     */
    public function test_can_register_admin(): void
    {
        // Prepare registration data
        $userData = [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        // Make request to API
        $response = $this->postJson('/api/auth/register', $userData);

        // Assert success and correct data structure
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'is_admin', 'created_at', 'updated_at']
            ]);

        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'is_admin' => true
        ]);
    }

    /**
     * Test admin login.
     */
    public function test_admin_can_login(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true
        ]);

        // Prepare login data
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ];

        // Make request to API
        $response = $this->postJson('/api/auth/login', $loginData);

        // Assert success and correct data structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
                'user' => ['id', 'name', 'email', 'is_admin']
            ]);
    }

    /**
     * Test getting authenticated user.
     */
    public function test_can_get_authenticated_user(): void
    {
        // Create admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Make authenticated request to API
        $response = $this->actingAs($admin)
            ->getJson('/api/auth/me');

        // Assert success and correct data
        $response->assertStatus(200)
            ->assertJsonPath('id', $admin->id)
            ->assertJsonPath('email', $admin->email);
    }

    /**
     * Test logout.
     */
    public function test_can_logout(): void
    {
        // Create admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Make authenticated request to API
        $response = $this->actingAs($admin)
            ->postJson('/api/auth/logout');

        // Assert success
        $response->assertStatus(200)
            ->assertJsonPath('message', 'Successfully logged out');
    }
}