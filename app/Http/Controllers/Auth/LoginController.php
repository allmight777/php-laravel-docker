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
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function connexion(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Tentative de connexion avec is_active = true
        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->professeur) {
                return redirect()->route('professeur.dashboard');
            } elseif ($user->eleve) {
                return redirect()->route('bulletin.index');
            }

            // Redirection par défaut
            return redirect()->intended('/');
        }

        // Échec de connexion
        if (\App\Models\User::where('email', $request->email)->where('is_active', false)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Votre compte est en attente de validation.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
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
