<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRendezVousRequest;
use App\Models\Creneau;
use App\Models\RendezVous;

class RendezVousController extends Controller
{
    public function index()
    {
        $rendezVousList = RendezVous::where('user_id', auth()->id())
            ->with('creneau')
            ->join('creneaus', 'rendez_vouses.creneau_id', '=', 'creneaus.id')
            ->orderBy('creneaus.date', 'desc')
            ->orderBy('creneaus.heure_debut', 'desc')
            ->select('rendez_vouses.*')
            ->get();

        return view('rendez-vous.index', compact('rendezVousList'));
    }

    public function store(StoreRendezVousRequest $request)
    {
        $creneau = Creneau::findOrFail($request->creneau_id);

        // 1. Vérifier que le créneau n'est pas passé
        if (
            $creneau->date . ' ' . $creneau->heure_debut
            < now()->format('Y-m-d H:i:s')
        ) {
            return back()->withErrors([
                'creneau_id' => 'Ce créneau est déjà passé.',
            ]);
        }

        // 2. Vérifier que le créneau n'est pas déjà réservé
        if (
            $creneau->rendezVous()
                ->where('statut', '!=', 'annule')
                ->exists()
        ) {
            return back()->withErrors([
                'creneau_id' => 'Ce créneau est déjà réservé.',
            ]);
        }

        // 3. Vérifier que le client n'a pas déjà
        // un rendez-vous qui chevauche ce créneau
        $rendezVous = RendezVous::where('user_id', auth()->id())
            ->where('statut', '!=', 'annule')
            ->with('creneau')
            ->get();

        foreach ($rendezVous as $rendezVousExistant) {
            if ($rendezVousExistant->creneau->chevauche($creneau)) {
                return back()->withErrors([
                    'creneau_id' => 'Vous avez déjà un rendez-vous qui chevauche ce créneau.',
                ]);
            }
        }

        // 4. Créer le rendez-vous
        RendezVous::create([
            'user_id' => auth()->id(),
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        return back()->with(
            'success',
            'Rendez-vous réservé avec succès.'
        );
    }

    public function destroy(RendezVous $rendezVous)
    {
        // Vérifier que le rendez-vous appartient
        // à l'utilisateur connecté
        if ($rendezVous->user_id !== auth()->id()) {
            abort(403);
        }

        // Annuler le rendez-vous
        $rendezVous->update([
            'statut' => 'annule',
        ]);

        return back()->with(
            'success',
            'Rendez-vous annulé avec succès.'
        );
    }
}