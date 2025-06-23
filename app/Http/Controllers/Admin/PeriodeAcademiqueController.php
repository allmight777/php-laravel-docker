<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\PeriodeAcademique;
use Illuminate\Http\Request;

class PeriodeAcademiqueController extends Controller
{
    public function index()
    {
        $periodes = PeriodeAcademique::with('annee')->get();

        return view('admin.periodes.index', compact('periodes'));
    }

    public function create()
    {
        $annees = AnneeAcademique::all();

        return view('admin.periodes.create', compact('annees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'annee_academique_id' => 'required|exists:annee_academique,id',
        ]);

        PeriodeAcademique::create($request->all());

        return redirect()->route('admin.periodes.index')->with('success', 'Période ajoutée avec succès.');
    }

    public function edit($id)
    {
        $periode = PeriodeAcademique::findOrFail($id);
        $annees = AnneeAcademique::all();

        return view('admin.periodes.edit', compact('periode', 'annees'));
    }

    public function update(Request $request, $id)
    {
        $periode = PeriodeAcademique::findOrFail($id);

        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'annee_academique_id' => 'required|exists:annee_academique,id',
        ]);

        $periode->update($request->all());

        return redirect()->route('admin.periodes.index')->with('success', 'Période mise à jour avec succès.');
    }

    public function destroy($id)
    {
        PeriodeAcademique::destroy($id);

        return back()->with('success', 'Période supprimée.');
    }
}
