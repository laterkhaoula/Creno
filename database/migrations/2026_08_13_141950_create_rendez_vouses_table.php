<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vouses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('creneau_id')
                ->constrained('creneaus')
                ->cascadeOnDelete();

            $table->enum('statut', [
                'en_attente',
                'confirme',
                'annule',
            ])->default('en_attente');

            $table->timestamps();

            // Un créneau ne peut être réservé qu'une seule fois
            $table->unique('creneau_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vouses');
    }
};