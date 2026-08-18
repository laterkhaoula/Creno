<?php

namespace Tests\Feature\Admin;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $creneau = Creneau::factory()->create();
        
        RendezVous::factory()->create([
            'user_id' => $client->id,
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee($client->name);
    }

    public function test_admin_can_update_rendez_vous_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $creneau = Creneau::factory()->create();
        
        $rendezVous = RendezVous::factory()->create([
            'user_id' => $client->id,
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.rendez-vous.update-status', $rendezVous), [
            'statut' => 'confirme',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('rendez_vouses', [
            'id' => $rendezVous->id,
            'statut' => 'confirme',
        ]);
    }
}
