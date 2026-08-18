<?php

namespace Tests\Feature;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendezVousTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Un client authentifié peut réserver un créneau disponible.
     */
    public function test_authenticated_user_can_reserve_available_creneau(): void
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

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('rendez_vouses', [
            'user_id' => $user->id,
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);
    }

    /**
     * Un créneau passé ne peut pas être réservé.
     */
    public function test_cannot_reserve_past_creneau(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau = Creneau::factory()->create([
            'date' => now()->subDays(1)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $response = $this->actingAs($user)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => $creneau->id,
            ]
        );

        $response->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseMissing('rendez_vouses', [
            'user_id' => $user->id,
            'creneau_id' => $creneau->id,
        ]);
    }

    /**
     * Un créneau déjà réservé ne peut pas être réservé une deuxième fois.
     */
    public function test_cannot_reserve_already_reserved_creneau(): void
    {
        $user1 = User::factory()->create([
            'role' => 'client',
        ]);

        $user2 = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        // Premier client réserve le créneau.
        $this->actingAs($user1)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => $creneau->id,
            ]
        );

        // Deuxième client essaie de réserver le même créneau.
        $response = $this->actingAs($user2)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => $creneau->id,
            ]
        );

        $response->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseCount('rendez_vouses', 1);
    }

    /**
     * Un client ne peut pas réserver deux créneaux qui se chevauchent.
     */
    public function test_client_cannot_reserve_overlapping_creneau(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau1 = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $creneau2 = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:15:00',
            'duree' => 30,
        ]);

        // Le client réserve le premier créneau.
        $this->actingAs($user)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => $creneau1->id,
            ]
        );

        // Le même client essaie de réserver un créneau qui se chevauche.
        $response = $this->actingAs($user)->post(
            route('rendez-vous.store'),
            [
                'creneau_id' => $creneau2->id,
            ]
        );

        $response->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseCount('rendez_vouses', 1);
    }

    /**
     * Un client peut annuler son propre rendez-vous.
     */
    public function test_client_can_cancel_own_rendez_vous(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $rendezVous = RendezVous::factory()->create([
            'user_id' => $user->id,
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($user)->delete(
            route('rendez-vous.destroy', $rendezVous)
        );

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('rendez_vouses', [
            'id' => $rendezVous->id,
            'user_id' => $user->id,
            'statut' => 'annule',
        ]);
    }

    /**
     * Un client ne peut pas annuler le rendez-vous d'un autre client.
     */
    public function test_client_cannot_cancel_another_client_rendez_vous(): void
    {
        $user1 = User::factory()->create([
            'role' => 'client',
        ]);

        $user2 = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $rendezVous = RendezVous::factory()->create([
            'user_id' => $user1->id,
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        // User 2 essaie d'annuler le rendez-vous de User 1.
        $response = $this->actingAs($user2)->delete(
            route('rendez-vous.destroy', $rendezVous)
        );

        // L'accès doit être refusé.
        $response->assertForbidden();

        // Le rendez-vous doit rester inchangé.
        $this->assertDatabaseHas('rendez_vouses', [
            'id' => $rendezVous->id,
            'user_id' => $user1->id,
            'statut' => 'en_attente',
        ]);
    }

    /**
     * Un client peut voir la liste de ses propres rendez-vous.
     */
    public function test_client_can_view_his_rendez_vous(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $creneau = Creneau::factory()->create();

        RendezVous::factory()->create([
            'user_id' => $user->id,
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($user)->get(route('rendez-vous.index'));

        $response->assertStatus(200);
        $response->assertViewIs('rendez-vous.index');
        $response->assertViewHas('rendezVousList');
        $response->assertSee('En attente');
    }
}