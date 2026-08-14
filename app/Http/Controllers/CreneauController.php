<?php

namespace App\Http\Controllers;

use App\Models\Creneau;

class CreneauController extends Controller
{
    public function index()
    {
        $creneaux = Creneau::disponibles()
            ->whereDoesntHave('rendezVous', function ($query) {
                $query->where('statut', '!=', 'annule');
            })
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();

        return view('creneaux.index', compact('creneaux'));
    }
}