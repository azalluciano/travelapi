<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the GET admin/login route.
     *
     * @return void
     */
    public function test_admin_login_route_displays_login_form()
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test the POST admin/login route with valid credentials.
     *
     * @return void
     */
    public function test_admin_login_route_authenticates_user()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.destinations.index'));
    }

    /**
     * Test the POST admin/login route with invalid credentials.
     *
     * @return void
     */
    public function test_admin_login_route_rejects_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test the GET admin/register route.
     *
     * @return void
     */
    public function test_admin_register_route_displays_registration_form()
    {
        $response = $this->get(route('admin.register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test the POST admin/register route with valid data.
     *
     * @return void
     */
    public function test_admin_register_route_creates_new_admin_user()
    {
        $userData = [
            'name' => 'Admin User',
            'email' => 'newadmin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/admin/register', $userData);

        $this->assertDatabaseHas('users', [
            'name' => 'Admin User',
            'email' => 'newadmin@example.com',
            'is_admin' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.destinations.index'));
    }

    /**
     * Test the POST admin/register route with invalid data.
     *
     * @return void
     */
    public function test_admin_register_route_validates_input()
    {
        $response = $this->post('/admin/register', [
            'name' => '', // Empty name to trigger validation error
            'email' => 'invalid-email',
            'password' => '123', // Too short
            'password_confirmation' => '456', // Doesn't match
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertDatabaseMissing('users', ['email' => 'invalid-email']);
        $this->assertGuest();
    }

    /**
     * Test the POST admin/logout route.
     *
     * @return void
     */
    public function test_admin_logout_route_logs_out_authenticated_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('admin.logout'));

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /**
     * Test that an authenticated admin is redirected from login page.
     *
     * @return void
     */
    public function test_authenticated_admin_is_redirected_from_login_page()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.login'));

        $response->assertRedirect('/admin/destinations');
    }
}
