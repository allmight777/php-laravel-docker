<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\ClasseMatiereProfesseur;
use App\Models\Eleve;
use App\Models\Note;
use App\Models\Professeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesseurController extends Controller
{
    public function affecterClasses(Request $request, Professeur $professeur)
    {
        $request->validate([
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
        ]);

        $professeur->classes()->sync($request->classes);

        return back()->with('success', 'Classes affectées avec succès');
    }

    // les trucs du professeurs

    // liste des professeurs
    public function index()
    {
        $professeurs = Professeur::with('user')->get();

        return view('admin.professeurs.index', compact('professeurs'));
    }

    public function dashboard()
    {
        return view('professeur.dashboard');
    }

    public function mesClasses()
    {

        $professeurId = Auth::user()->professeur->id;

        $classes = ClasseMatiereProfesseur::with(['classe', 'matiere'])
            ->where('professeur_id', $professeurId)
            ->get();

        return view('professeur.classes', compact('classes'));
    }

    public function elevesParClasse(Classe $classe)
    {

        $professeurId = Auth::user()->professeur->id;

        $affectation = ClasseMatiereProfesseur::where('classe_id', $classe->id)
            ->where('professeur_id', $professeurId)
            ->firstOrFail();

        $eleves = Eleve::with('user')
            ->where('classe_id', $classe->id)
            ->get();

        $matiere = $affectation->matiere;

        return view('professeur.eleves', compact('classe', 'eleves', 'matiere'));
    }

    public function enregistrerNotes(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'notes' => 'required|array',
        ]);

        $professeurId = Auth::user()->professeur->id;

        $affectation = ClasseMatiereProfesseur::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->where('professeur_id', $professeurId)
            ->firstOrFail();

        // Enregistrer les notes
        foreach ($request->notes as $eleveId => $notes) {
            // Interrogations
            foreach (['interro1', 'interro2', 'interro3'] as $type) {
                if (! empty($notes[$type])) {
                    Note::updateOrCreate(
                        [
                            'eleve_id' => $eleveId,
                            'matiere_id' => $request->matiere_id,
                            'type_evaluation' => 'interrogation',
                            'periode' => 'trimestre1',
                            'nom_evaluation' => $type,
                        ],
                        ['valeur' => $notes[$type]]
                    );

                }
            }

            // Devoirs
            foreach (['devoir1', 'devoir2'] as $type) {
                if (! empty($notes[$type])) {
                    Note::updateOrCreate(
                        [
                            'eleve_id' => $eleveId,
                            'matiere_id' => $request->matiere_id,
                            'type_evaluation' => 'interrogation',
                            'periode' => 'trimestre1',
                            'nom_evaluation' => $type,
                        ],
                        ['valeur' => $notes[$type]]
                    );

                }
            }
        }

        return redirect()->back()->with('success', 'Notes enregistrées avec succès!');
    }
}
