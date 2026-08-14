<?php

namespace Tests\Feature;

use App\Models\Creneau;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreRendezVousRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_valid_creneau_id(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $response = $this->actingAs($user)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => $creneau->id,
            ]
        );

        $response->assertSessionDoesntHaveErrors('creneau_id');
    }

    public function test_creneau_id_is_required(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $response = $this->actingAs($user)->post(
            route('rendez-vous.store'),
            []
        );

        $response->assertSessionHasErrors('creneau_id');
    }

    public function test_creneau_id_must_exist(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $response = $this->actingAs($user)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => 99999,
            ]
        );

        $response->assertSessionHasErrors('creneau_id');
    }
}