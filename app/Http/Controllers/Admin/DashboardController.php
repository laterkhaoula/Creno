<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $rendezVousList = RendezVous::with(['user', 'creneau'])
            ->join('creneaus', 'rendez_vouses.creneau_id', '=', 'creneaus.id')
            ->orderBy('creneaus.date', 'desc')
            ->orderBy('creneaus.heure_debut', 'desc')
            ->select('rendez_vouses.*')
            ->paginate(15);

        return view('admin.dashboard', compact('rendezVousList'));
    }

    public function updateStatus(Request $request, RendezVous $rendezVous)
    {
        $request->validate([
            'statut' => ['required', 'in:en_attente,confirme,annule'],
        ]);

        $rendezVous->update([
            'statut' => $request->statut,
        ]);

        return back()->with('success', 'Le statut du rendez-vous a été mis à jour.');
    }
}
