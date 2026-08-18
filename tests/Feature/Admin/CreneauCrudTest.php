<?php

namespace Tests\Feature\Admin;

use App\Models\Creneau;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class CreneauCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_creneaux_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Creneau::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.creneaux.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.creneaux.index');
        $response->assertViewHas('creneaux');
    }

    public function test_admin_can_create_creneau(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $date = Carbon::now()->addDays(2)->format('Y-m-d');
        
        $response = $this->actingAs($admin)->post(route('admin.creneaux.store'), [
            'date' => $date,
            'heure_debut' => '10:00',
            'duree' => 30,
        ]);

        $response->assertRedirect(route('admin.creneaux.index'));
        $this->assertDatabaseHas('creneaus', [
            'date' => $date,
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);
    }

    public function test_admin_can_update_creneau(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $creneau = Creneau::factory()->create();
        
        $newDate = Carbon::now()->addDays(5)->format('Y-m-d');

        $response = $this->actingAs($admin)->put(route('admin.creneaux.update', $creneau), [
            'date' => $newDate,
            'heure_debut' => '14:00',
            'duree' => 60,
        ]);

        $response->assertRedirect(route('admin.creneaux.index'));
        $this->assertDatabaseHas('creneaus', [
            'id' => $creneau->id,
            'date' => $newDate,
            'heure_debut' => '14:00:00',
            'duree' => 60,
        ]);
    }

    public function test_admin_can_delete_creneau_without_rendez_vous(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $creneau = Creneau::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.creneaux.destroy', $creneau));

        $response->assertRedirect(route('admin.creneaux.index'));
        $this->assertDatabaseMissing('creneaus', [
            'id' => $creneau->id,
        ]);
    }
}
