<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountRejected;
use App\Models\Professeur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    public function pendingUsers()
    {
        $users = User::where('is_active', false)
            ->with(['eleve.classe', 'eleve.anneeAcademique', 'professeur.matieres'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
            'professeur.matieres',
        ])
            ->findOrFail($id);

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

        return back()
            ->with('success', "Le compte de {$fullName} a été rejeté.");
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
}
