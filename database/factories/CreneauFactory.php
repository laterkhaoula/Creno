<?php

namespace Database\Factories;

use App\Models\Creneau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Creneau>
 */
class CreneauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('today', '+30 days')->format('Y-m-d'),
            'heure_debut' => fake()->time('H:i:s'),
            'duree' => 30,
        ];
    }
}