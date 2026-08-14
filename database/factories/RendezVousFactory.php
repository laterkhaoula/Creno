<?php

namespace Database\Factories;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RendezVousFactory extends Factory
{
    protected $model = RendezVous::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'creneau_id' => Creneau::factory(),
            'statut' => 'en_attente',
        ];
    }
}