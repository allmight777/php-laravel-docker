<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Note;

class BulletinController extends Controller
{

     // Affiche le bulletin de l'élève connecté
       public function dashboard()
    {
        return view('bulletin.index');
    }


    public function show()
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
