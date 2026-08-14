<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Creneau extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'heure_debut',
        'duree',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relation avec les rendez-vous.
     */
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    /**
     * Retourne les créneaux futurs.
     */
    public function scopeDisponibles(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where(function ($query) use ($now) {
                $query
                    ->where('date', '>', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query
                            ->where('date', $now->toDateString())
                            ->where('heure_debut', '>=', $now->format('H:i:s'));
                    });
            })
            ->whereDoesntHave('rendezVous', function ($query) {
                $query->where('statut', '!=', 'annule');
            })
            ->orderBy('date')
            ->orderBy('heure_debut');
    }

    /**
     * Retourne les créneaux déjà passés.
     */
    public function scopePasses(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where(function ($query) use ($now) {
                $query
                    ->where('date', '<', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query
                            ->where('date', $now->toDateString())
                            ->where('heure_debut', '<', $now->format('H:i:s'));
                    });
            });
    }

    /**
     * Vérifie si ce créneau chevauche un autre créneau.
     */
    public function chevauche(Creneau $autre): bool
    {
        $debut1 = Carbon::parse(
            $this->date->format('Y-m-d') . ' ' . $this->heure_debut
        );

        $fin1 = $debut1->copy()->addMinutes($this->duree);

        $debut2 = Carbon::parse(
            $autre->date->format('Y-m-d') . ' ' . $autre->heure_debut
        );

        $fin2 = $debut2->copy()->addMinutes($autre->duree);

        return $debut1 < $fin2 && $debut2 < $fin1;
    }
}