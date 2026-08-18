<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IsAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Creating a dummy route for testing the middleware
        \Illuminate\Support\Facades\Route::get('/admin/test', function () {
            return 'OK';
        })->middleware(['web', 'auth', 'is_admin']);

        $response = $this->actingAs($admin)->get('/admin/test');

        $response->assertStatus(200);
        $response->assertSee('OK');
    }

    public function test_client_cannot_access_admin_routes(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
        ]);

        \Illuminate\Support\Facades\Route::get('/admin/test', function () {
            return 'OK';
        })->middleware(['web', 'auth', 'is_admin']);

        $response = $this->actingAs($client)->get('/admin/test');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_admin_routes(): void
    {
        \Illuminate\Support\Facades\Route::get('/admin/test', function () {
            return 'OK';
        })->middleware(['web', 'auth', 'is_admin']);

        $response = $this->get('/admin/test');

        $response->assertRedirect('/login');
    }
}
