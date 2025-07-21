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
    // Validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        'is_active' => true,
    ];

    if (auth()->attempt($credentials, $request->filled('remember'))) {
        $user = auth()->user();

        // Redirection selon les rôles (relations)
        if ($user->administrateur()->exists()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->professeur()->exists()) {
            return redirect()->route('professeur.dashboard');
        }

        if ($user->eleve()->exists()) {
            return redirect()->route('bulletin.index');
        }

        // Redirection par défaut
        return redirect()->intended('/');
    }

    // Si le compte est inactif
    if (User::where('email', $request->email)->where('is_active', false)->exists()) {
        return back()->withErrors([
            'email' => 'Votre compte est en attente de validation.',
        ])->withInput();
    }

    // Échec d'authentification
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
