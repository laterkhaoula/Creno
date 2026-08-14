<?php

namespace Tests\Feature;

use App\Models\Creneau;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreneauTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que le scope disponibles()
     * retourne uniquement les créneaux futurs.
     */
    public function test_disponibles_scope_returns_future_creneaux(): void
    {
        Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        Creneau::factory()->create([
            'date' => now()->subDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $creneaux = Creneau::disponibles()->get();

        $this->assertCount(1, $creneaux);
    }

    /**
     * Vérifie que le scope passes()
     * retourne uniquement les créneaux passés.
     */
    public function test_passes_scope_returns_past_creneaux(): void
    {
        Creneau::factory()->create([
            'date' => now()->subDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $creneaux = Creneau::passes()->get();

        $this->assertCount(1, $creneaux);
    }

    /**
     * Vérifie que deux créneaux qui se chevauchent
     * sont correctement détectés.
     */
    public function test_creneau_detects_overlap(): void
    {
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

        $this->assertTrue(
            $creneau1->chevauche($creneau2)
        );
    }

    /**
     * Vérifie que deux créneaux qui se suivent exactement
     * ne sont pas considérés comme un chevauchement.
     */
    public function test_creneau_does_not_overlap_when_one_starts_when_other_ends(): void
    {
        $creneau1 = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'duree' => 30,
        ]);

        $creneau2 = Creneau::factory()->create([
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:30:00',
            'duree' => 30,
        ]);

        $this->assertFalse(
            $creneau1->chevauche($creneau2)
        );
    }
}