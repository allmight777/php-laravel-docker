<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Redirection par défaut après connexion.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gère la tentative de connexion.
     */
public function connexion(Request $request)
{
    // Validation des données reçues
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Préparer les credentials avec is_active = true pour empêcher connexion si inactif
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        'is_active' => true,
    ];

    // Tentative d'authentification
    if (auth()->attempt($credentials, $request->filled('remember'))) {
        $user = auth()->user();

        // Redirection selon le rôle
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->professeur) {
            return redirect()->route('professeur.dashboard');
        } elseif ($user->eleve) {
            return redirect()->route('bulletin.index');
        }

        // Par défaut, redirige vers la page d'accueil
        return redirect()->intended('/');
    }

    // Vérification si compte inactif
    if (User::where('email', $request->email)->where('is_active', false)->exists()) {
        return back()->withErrors([
            'email' => 'Votre compte est en attente de validation.',
        ])->withInput();
    }

    // Si on arrive ici, échec d'authentification classique
    return back()->withErrors([
        'email' => 'Identifiants incorrects.',
    ])->withInput();
}


    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
