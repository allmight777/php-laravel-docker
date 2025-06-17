<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\ClasseMatiereProfesseur;
use App\Models\Eleve;
use App\Models\Note;
use App\Models\AnneeAcademique;
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
         // Récupération de l'ID du professeur connecté
        $professeurId = Auth::user()->professeur->id;
        //Récupération des classes et matières associées au professeur
        $classes = ClasseMatiereProfesseur::with(['classe', 'matiere'])
            ->where('professeur_id', $professeurId)
            ->get();

        return view('professeur.classes', compact('classes'));
    }
     //Affiche les eleves d'une classe specifique
    public function elevesParClasse(Classe $classe)
    {
         // Vérification que le professeur est bien affecté à cette classe
        $professeurId = Auth::user()->professeur->id;

        $affectation = ClasseMatiereProfesseur::where('classe_id', $classe->id)
            ->where('professeur_id', $professeurId)
            ->firstOrFail();//Genere une erreur 404 si non
        // Récupération des élèves de la classe avec leurs infos utilisateur
        $eleves = Eleve::with('user')
            ->where('classe_id', $classe->id)
            ->get();
         // Récupération de la matière enseignée
        $matiere = $affectation->matiere;
        // Retourne la vue avec les données
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

    public function dashboar()

    {
         $classes = Classe::all();
        $annees = AnneeAcademique::all();
        return view('bulletin.index');
    }
    public function showBulletin()
    {
       //voir l'eleve actu connecté
        $eleve = auth()->user();
        
        // Double vérification que c'est bien un élève
        if (!$eleve instanceof \App\Models\Eleve) {
            abort(403, 'Accès non autorisé.');
        }
        
        // les notes avec les matières associées
        $notes = Note::with('matieres')
                    ->where('eleve_id', $eleve->id)
                    ->get()
                    ->groupBy('matiere.nom');
        
        // Calcul de la moyenne générale
        $moyenneGenerale = $this->calculerMoyenneGenerale($notes);
        
        return view('bulletin.show', [
            'eleve' => $eleve,
            'notesParMatiere' => $notes,
            'moyenneGenerale' => $moyenneGenerale
        ]);
    }
    
    
    // Calcule la moyenne générale
    
    private function calculerMoyenneGenerale($notesParMatiere)
    {
        $total = 0;
        $count = 0;
        
        foreach ($notesParMatiere as $matiere => $notes) {
            $moyenneMatiere = $notes->avg('valeur');
            $total += $moyenneMatiere;
            $count++;
        }
        
        return $count > 0 ? round($total / $count, 2) : 0;
    }
}
