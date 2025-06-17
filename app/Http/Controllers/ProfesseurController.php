<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\AnneeAcademique;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Note;
use App\Models\PeriodeAcademique;
use App\Models\Professeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function index()
    {
        $professeurs = Professeur::with('user')->get();

        return view('admin.professeurs.index', compact('professeurs'));
    }

    public function dashboard()
    {
        $annees = AnneeAcademique::all();

        return view('professeur.dashboard', compact('annees'));
    }

    public function mesClasses($anneeId)
    {
        $professeurId = Auth::user()->professeur->id;

        $classes = Affectation::with(['classe', 'matiere'])
            ->where('professeur_id', $professeurId)
            ->where('annee_academique_id', $anneeId)
            ->get()
            ->groupBy('classe_id');

        $annee = AnneeAcademique::findOrFail($anneeId);

        return view('professeur.classes', compact('classes', 'annee'));
    }

    public function elevesParClasse($anneeId, $classeId)
    {
        $professeurId = Auth::user()->professeur->id;

        $affectations = Affectation::where('classe_id', $classeId)
            ->where('professeur_id', $professeurId)
            ->where('annee_academique_id', $anneeId)
            ->with([
                'matiere' => function ($query) use ($classeId) {
                    $query->with([
                        'ClasseMatiereProfesseur' => function ($q) use ($classeId) {
                            $q->where('classe_id', $classeId);
                        },
                    ]);
                },
            ])
            ->get();

        $eleves = Eleve::with('user')
            ->where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeId)
            ->get();

        $classe = Classe::findOrFail($classeId);
        $annee = AnneeAcademique::findOrFail($anneeId);
        $periodes = PeriodeAcademique::where('annee_academique_id', $anneeId)->get();

        // Récupérer la matière sélectionnée ou la première par défaut
        $selectedMatiereId = request('matiere_id', $affectations->first()->matiere->id ?? null);
        $selectedPeriodeId = request('periode_id', $periodes->first()->id ?? null);

        // Récupérer toutes les notes existantes pour la période sélectionnée
        $notesExistantes = [];
        if ($selectedMatiereId && $selectedPeriodeId) {
            $notes = Note::whereIn('eleve_id', $eleves->pluck('id'))
                ->where('matiere_id', $selectedMatiereId)
                ->where('periode_id', $selectedPeriodeId)
                ->get();

            foreach ($notes as $note) {
                $notesExistantes[$note->eleve_id . '_' . $note->nom_evaluation] = $note;
            }
        }

        return view('professeur.eleves', compact(
            'classe',
            'eleves',
            'affectations',
            'annee',
            'periodes',
            'notesExistantes',
            'selectedMatiereId',
            'selectedPeriodeId'
        ));
    }

    public function saisirNotes(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'periode_id' => 'required|exists:periodes_academiques,id',
            'annee_academique_id' => 'required|exists:annee_academique,id',
            'notes' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $coefficient = DB::table('classe_matiere_professeur')
                ->where('classe_id', $request->classe_id)
                ->where('matiere_id', $request->matiere_id)
                ->value('coefficient') ?? 1;

            foreach ($request->notes as $eleveId => $notes) {
                foreach ($notes as $type => $valeur) {
                    if ($valeur !== null && $valeur !== '' && is_numeric($valeur)) {
                        $valeur = floatval($valeur);
                        $typeEvaluation = strpos($type, 'interro') !== false ? 'interrogation' : 'devoir';

                        Note::updateOrCreate(
                            [
                                'eleve_id' => $eleveId,
                                'matiere_id' => $request->matiere_id,
                                'periode_id' => $request->periode_id,
                                'type_evaluation' => $typeEvaluation,
                                'nom_evaluation' => $type,
                            ],
                            [
                                'valeur' => $valeur,
                                'is_locked' => true,
                            ]
                        );
                    }
                }

                $notesEnregistrees = Note::where('eleve_id', $eleveId)
                    ->where('matiere_id', $request->matiere_id)
                    ->where('periode_id', $request->periode_id)
                    ->get();

                $interroNotes = $notesEnregistrees->where('type_evaluation', 'interrogation')->pluck('valeur')->all();
                $devoirNotes = $notesEnregistrees->where('type_evaluation', 'devoir')->pluck('valeur')->all();

                $interroCount = count($interroNotes);
                $devoirCount = count($devoirNotes);

                $interroMoy = $interroCount > 0 ? array_sum($interroNotes) / $interroCount : 0;
                $devoirMoy = $devoirCount > 0 ? array_sum($devoirNotes) : 0;

                if ($interroCount > 0 && $devoirCount > 0) {
                    $moyenne = ($interroMoy + $devoirMoy) / 3;
                } elseif ($interroCount > 0 && $devoirCount == 0) {
                    $moyenne = $interroMoy;
                } elseif ($interroCount == 0 && $devoirCount > 0) {
                    $moyenne = $devoirMoy;
                } else {
                    $moyenne = 0;
                }

                $moyenneCoefficient = $moyenne * $coefficient;

                Bulletin::updateOrCreate(
                    [
                        'eleve_id' => $eleveId,
                        'periode_id' => $request->periode_id,
                        'matiere_id' => $request->matiere_id,
                    ],
                    [
                        'total' => $moyenne,
                        'moyenne' => $moyenne,
                        'moyenne_coefficient' => $moyenneCoefficient,
                        'statut' => $moyenne >= 12 ? 1 : 0,
                        'rang' => 0,
                    ]
                );
            }

            $this->updateRanks($request->periode_id, $request->matiere_id);
            $this->calculateAnnualAverages($request->classe_id, $request->annee_academique_id);
        });

        return back()->with('success', 'Notes enregistrées avec succès!');
    }

    private function updateRanks($periodeId, $matiereId)
    {
        $bulletins = Bulletin::where('periode_id', $periodeId)
            ->where('matiere_id', $matiereId)
            ->orderBy('moyenne', 'desc')
            ->get();

        $rank = 1;
        foreach ($bulletins as $bulletin) {
            $bulletin->update(['rang' => $rank]);
            $rank++;
        }
    }

    private function calculateAnnualAverages($classeId, $anneeId)
    {
        $eleves = Eleve::where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeId)
            ->get();

        foreach ($eleves as $eleve) {
            $periodes = PeriodeAcademique::where('annee_academique_id', $anneeId)->get();
            $totalMoyenne = 0;
            $periodeCount = 0;

            foreach ($periodes as $periode) {
                $bulletin = Bulletin::where('eleve_id', $eleve->id)
                    ->where('periode_id', $periode->id)
                    ->first();

                if ($bulletin) {
                    $totalMoyenne += $bulletin->moyenne;
                    $periodeCount++;
                }
            }

            if ($periodeCount > 0) {
                $moyenneAnnuelle = $totalMoyenne / $periodeCount;
                // Stockage à venir si besoin
            }
        }
    }

    public function dashboar()
    {
        $classes = Classe::all();
        $annees = AnneeAcademique::all();
        return view('bulletin.index');
    }

    public function showBulletin()
    {
        $eleve = auth()->user();

        if (!$eleve instanceof \App\Models\Eleve) {
            abort(403, 'Accès non autorisé.');
        }

        $notes = Note::with('matieres')
            ->where('eleve_id', $eleve->id)
            ->get()
            ->groupBy('matiere.nom');

        $moyenneGenerale = $this->calculerMoyenneGenerale($notes);

        return view('bulletin.show', [
            'eleve' => $eleve,
            'notesParMatiere' => $notes,
            'moyenneGenerale' => $moyenneGenerale
        ]);
    }

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
