<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreneauRequest;
use App\Models\Creneau;
use Illuminate\Http\Request;

class CreneauController extends Controller
{
    public function index()
    {
        $creneaux = Creneau::orderBy('date', 'desc')->orderBy('heure_debut', 'asc')->paginate(15);
        return view('admin.creneaux.index', compact('creneaux'));
    }

    public function create()
    {
        return view('admin.creneaux.create');
    }

    public function store(StoreCreneauRequest $request)
    {
        Creneau::create($request->validated());

        return redirect()->route('admin.creneaux.index')
            ->with('success', 'Créneau créé avec succès.');
    }

    public function edit(Creneau $creneau)
    {
        return view('admin.creneaux.edit', compact('creneau'));
    }

    public function update(StoreCreneauRequest $request, Creneau $creneau)
    {
        $creneau->update($request->validated());

        return redirect()->route('admin.creneaux.index')
            ->with('success', 'Créneau mis à jour avec succès.');
    }

    public function destroy(Creneau $creneau)
    {
        if ($creneau->rendezVous()->where('statut', '!=', 'annule')->exists()) {
            return back()->with('error', 'Impossible de supprimer un créneau avec des rendez-vous actifs.');
        }

        $creneau->delete();

        return redirect()->route('admin.creneaux.index')
            ->with('success', 'Créneau supprimé avec succès.');
    }
}
