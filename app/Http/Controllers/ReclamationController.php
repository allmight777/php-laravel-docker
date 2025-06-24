<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReclamationNotification;

class ReclamationController extends Controller
{
    // ➤ Formulaire de réclamation (étudiant)
    public function create()
    {
        $eleve = Auth::user()->eleve;
        $notes = Note::where('eleve_id', $eleve->id)
                   ->with('matiere')
                   ->get();
        return view('reclamations.create', compact('notes'));
    }

    // ➤ Enregistrement de la réclamation (étudiant)
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'note_id' => 'required|exists:notes,id',
        ]);

        $note = Note::findOrFail($request->note_id);

        $reclamation = Reclamation::create([
            'eleve_id' => Auth::user()->eleve->id,
            'professeur_id' => $note->professeur_id,
            'message' => $request->message,
            'matiere_id' => $note->matiere_id,
            'note_id' => $note->id,
            'statut' => 'en_attente'
        ]);

        // Envoi d'une notification au professeur concerné
        $professeur = User::find($note->professeur_id);
        if ($professeur) {
            $professeur->notify(new ReclamationNotification($reclamation));
        }

        return redirect()->route('reclamations.create')->with('success', 'Réclamation envoyée avec succès !');
    }

    // ➤ Liste des réclamations pour professeurs ou admin
    public function index()
    {
        $user = Auth::user();

        $reclamations = Reclamation::query()
            ->when($user->hasRole('professeur'), function($q) use ($user) {
                $q->where('professeur_id', $user->id);
            })
            ->with(['eleve.user', 'matiere', 'note'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reclamations.index', compact('reclamations'));
    }

    // ➤ Traitement par le professeur (accepter ou rejeter)
    public function traiter(Reclamation $reclamation, Request $request)
    {
        $request->validate([
            'statut' => 'required|in:traitee,rejetee',
            'reponse' => 'nullable|string|max:1000',
        ]);

        $reclamation->update([
            'statut' => $request->statut,
            'admin_id' => Auth::id(),
            'reponse' => $request->reponse
        ]);

        // Optionnel : envoyer une notification à l'étudiant

        return back()->with('success', 'Réclamation traitée avec succès.');
    }
}
