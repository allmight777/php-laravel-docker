<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountRejected;
use App\Models\Affectation;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Vérification admin
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (! auth()->user()->is_admin) {
                abort(403, 'Accès réservé aux administrateurs');
            }

            return $next($request);
        });
    }

    //
    public function dashboard()
    {
        $pendingUsers = User::where('is_active', false)
            ->with(['eleve.classe', 'eleve.anneeAcademique', 'professeur.matieres'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $counts = [
            'pending' => User::where('is_active', false)->count(),
            'active' => User::where('is_active', true)->count(),
            'teachers' => Professeur::count(),
        ];

        return view('admin.dashboard', compact('pendingUsers', 'counts'));
    }

    /**
     * Liste des utilisateurs en attente
     */
    public function pendingUsers(Request $request)
    {
        $query = User::where('is_active', false)
            ->with(['eleve.classe', 'eleve.anneeAcademique', 'professeur.matieres'])
            ->orderBy('created_at', 'desc');

        if ($request->has('type')) {
            if ($request->type === 'eleve') {
                $query->whereHas('eleve');
            } elseif ($request->type === 'professeur') {
                $query->whereHas('professeur');
            }
        }

        $users = $query->paginate(10);

        return view('admin.users.pending', compact('users'));
    }

    /**
     * Liste des utilisateurs actifs
     */
    public function activeUsers()
    {
        $users = User::where('is_active', true)
            ->with(['eleve.classe', 'eleve.anneeAcademique', 'professeur.matieres'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.active', compact('users'));
    }

    /**
     * Affichage d'un utilisateur
     */
    public function showUser($id)
    {
        $user = User::with([
            'eleve.classe',
            'eleve.anneeAcademique',
            'affectations.classe',
            'affectations.matiere',
            'affectations.anneeAcademique',
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Approbation d'un utilisateur
     */
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        Mail::to($user->email)->send(new AccountApproved($user));

        return back()
            ->with('success', "Le compte de {$this->fullName($user)} a été approuvé.");
    }

    /**
     * Rejet d'un utilisateur
     */
    public function rejectUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($id);
        $fullName = $this->fullName($user);

        Mail::to($user->email)->send(new AccountRejected($user, $request->reason));

        if ($user->professeur) {
            $user->professeur->classes()->detach();
            $user->professeur->matieres()->detach();
            $user->professeur->delete();
        }

        if ($user->eleve) {
            $user->eleve->delete();
        }

        $user->delete();

        return back()->with('success', "Le compte de {$fullName} a été rejeté.");
    }

    /**
     * Désactivation d'un utilisateur
     */
    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => false]);

        return back()
            ->with('success', "Le compte de {$this->fullName($user)} a été désactivé.");
    }

    /**
     * Méthode pour le nom complet
     */
    private function fullName(User $user)
    {
        return trim("{$user->prenom} {$user->nom}");
    }

    // Gestion des affectations professeurs/classes/matières par année scolaires
    public function affectation($id)
    {

        // afficher les classes et matières à affecter à un professeur donné.

        $professeur = User::findOrFail($id);
        $annees = AnneeAcademique::all();

        $classes = Classe::all()->map(function ($classe) {
            $classe->matieres = DB::table('classe_matiere_professeur')
                ->where('classe_id', $classe->id)
                ->whereNull('professeur_id')
                ->join('matieres', 'matieres.id', '=', 'classe_matiere_professeur.matiere_id')
                ->select('matieres.id', 'matieres.nom', 'matieres.code')
                ->distinct()
                ->get();

            return $classe;
        });

        return view('admin.professeurs.affectation', compact('professeur', 'annees', 'classes'));
    }

    // affectation profs

    public function storeAffectation(Request $request, $id)
    {
        $professeur = User::findOrFail($id);
        $annee_id = $request->annee_scolaire_id;

        $errors = [];

        foreach ($request->affectations ?? [] as $combo) {
            [$classe_id, $matiere_id] = explode('-', $combo);

            $matiere = Matiere::find($matiere_id);
            $classe = Classe::find($classe_id);
            $annee = AnneeAcademique::find($annee_id);

            // Vérifier si l'affectation existe déjà
            $exists = Affectation::where('classe_id', $classe_id)
                ->where('matiere_id', $matiere_id)
                ->where('annee_academique_id', $annee_id)
                ->exists();

            if ($exists) {
                $matiereNom = $matiere ? $matiere->nom : "ID $matiere_id";
                $classeNom = $classe ? $classe->nom : "ID $classe_id";
                $anneeLibelle = $annee ? $annee->libelle : "ID $annee_id";

                $errors[] = "La matière « $matiereNom » est déjà attribuée pour la classe « $classeNom » pour l'année scolaire « $anneeLibelle ».";
            } else {
                Affectation::create([
                    'professeur_id' => $professeur->id,
                    'classe_id' => $classe_id,
                    'matiere_id' => $matiere_id,
                    'annee_academique_id' => $annee_id,
                ]);
            }
        }

        if (count($errors) > 0) {
            return redirect()->back()->withErrors($errors);
        }

        return redirect()->route('professeurs.index')->with('success', 'Affectations enregistrées');
    }

    // Update affectation
    public function editAffectation($id)
    {
        $professeur = User::findOrFail($id);

        // Récupérer toutes les années académiques
        $annees = AnneeAcademique::all();

        // Récupérer les affectations groupées par année scolaire pour ce professeur
        $affectations = Affectation::with(['classe', 'matiere'])
            ->where('professeur_id', $professeur->id)
            ->get()
            ->groupBy('annee_academique_id');

        // Sélectionner une année par défaut (la plus récente, ou la première dans la liste)
        $anneeSelectionneeId = $annees->last()?->id ?? $annees->first()?->id;

        return view('admin.professeurs.editAffectation', compact(
            'professeur',
            'annees',
            'affectations',
            'anneeSelectionneeId'
        ));
    }

    public function updateAffectation(Request $request, $id)
    {
        $professeur = User::findOrFail($id);
        $annee_id = $request->annee_academique_id;

        // Supprimer toutes les affectations existantes pour ce prof et cette année
        Affectation::where('professeur_id', $professeur->id)
            ->where('annee_academique_id', $annee_id)
            ->delete();

        $affectations = $request->input('affectations', []);

        foreach ($affectations as $combo) {
            [$classe_id, $matiere_id] = explode('-', $combo);

            // Vérifier si cette matière est déjà affectée à un autre prof pour cette année
            $exists = Affectation::where('classe_id', $classe_id)
                ->where('matiere_id', $matiere_id)
                ->where('annee_academique_id', $annee_id)
                ->exists();

            if (! $exists) {
                Affectation::create([
                    'professeur_id' => $professeur->id,
                    'classe_id' => $classe_id,
                    'matiere_id' => $matiere_id,
                    'annee_academique_id' => $annee_id,
                ]);
            }
        }

        return redirect()->route('professeurs.index')->with('success', 'Affectations mises à jour avec succès.');
    }

    // Gestion affectation eleves par annee et classe

    public function affectationAnnees()
    {
        $annees = AnneeAcademique::all();

        return view('admin.affectation.annees', compact('annees'));
    }

    public function affectationClasses($anneeId)
    {
        $annee = AnneeAcademique::findOrFail($anneeId);
        $classes = Classe::all();

        return view('admin.affectation.classes', compact('annee', 'classes'));
    }

    public function affectationEleves($anneeId, $classeId)
    {
        $annee = AnneeAcademique::findOrFail($anneeId);
        $classe = Classe::findOrFail($classeId);

        // Sélectionne les élèves actifs qui n'ont pas encore une ligne eleve avec cette classe et année
        // Ici, on doit exclure les eleves déjà affectés à cette classe et année
        $elevesAffectes = Eleve::where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeId)
            ->pluck('user_id')
            ->toArray();

        $eleves = User::whereHas('eleve')
            ->where('is_active', true)
            ->whereNotIn('id', $elevesAffectes)
            ->get();

        return view('admin.affectation.eleves', compact('annee', 'classe', 'eleves'));
    }

    public function assignerElevesClasse(Request $request)
    {
        $request->validate([
            'eleves' => 'required|array',
            'classe_id' => 'required|exists:classes,id',
            'annee_id' => 'required|exists:annee_academique,id',
        ]);

        foreach ($request->eleves as $userId) {
            $exists = Eleve::where('user_id', $userId)
                ->where('classe_id', $request->classe_id)
                ->where('annee_academique_id', $request->annee_id)
                ->exists();

            if (! $exists) {
                Eleve::create([
                    'user_id' => $userId,
                    'classe_id' => $request->classe_id,
                    'annee_academique_id' => $request->annee_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Élèves affectés avec succès.');
    }

    // Liste des années scolaires
    public function anneesScolaires()
    {
        $annees = AnneeAcademique::all();

        return view('admin.anneesScolaires.index', compact('annees'));
    }

    // Affichage formulaire année scolaire
    public function createAnnee()
    {
        return view('admin.anneesScolaires.create');
    }

    // Enregistrer nouvelle année scolaire
    public function storeAnnee(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:annee_academique,libelle',
        ]);

        AnneeAcademique::create([
            'libelle' => $request->libelle,
        ]);

        return redirect()->route('admin.annees.index')->with('success', 'Année scolaire créée avec succès.');
    }

    // Formulaire édition année scolaire
    public function editAnnee($id)
    {
        $annee = AnneeAcademique::findOrFail($id);

        return view('admin.anneesScolaires.edit', compact('annee'));
    }

    // Mise à jour année scolaire
    public function updateAnnee(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:annee_academique,libelle,'.$id,
        ]);

        $annee = AnneeAcademique::findOrFail($id);
        $annee->libelle = $request->libelle;
        $annee->save();

        return redirect()->route('admin.annees.index')->with('success', 'Année scolaire mise à jour avec succès.');
    }

    // Supprimer annee

    public function destroyAnnee($id)
    {
        $annee = AnneeAcademique::findOrFail($id);
        $annee->delete();

        return redirect()->route('admin.annees.index')->with('success', 'Année scolaire supprimée avec succès.');
    }

    // Gestin des comptes utilisateurs (edition)

    public function listUsers()
    {
        $users = User::with(['professeur', 'eleve'])
            ->orderBy('nom')
            ->paginate(10);

        return view('admin.users.listeElevesProfs', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'telephone' => 'nullable|string|max:20',
            'date_de_naissance' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [];

        if ($request->filled('nom')) {
            $data['nom'] = $request->nom;
        }

        if ($request->filled('prenom')) {
            $data['prenom'] = $request->prenom;
        }

        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }

        if ($request->filled('telephone')) {
            $data['telephone'] = $request->telephone;
        }

        if ($request->filled('date_de_naissance')) {
            $data['date_de_naissance'] = $request->date_de_naissance;
        }

        // Photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('avatars', 'public');
            $data['photo'] = $photoPath;
        }

        // Mot de passe
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès!');
    }
}
