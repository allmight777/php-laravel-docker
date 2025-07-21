<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        // Seuls les invités peuvent accéder sauf pour logout
        $this->middleware('guest')->except('logout');
    }

    public function connexion(Request $request)
    {
        // Validation des champs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Préparation des identifiants avec is_active = true
        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = true;

        // Tentative de connexion
        if (auth()->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Redirection selon le rôle
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->professeur()->exists()) {
                return redirect()->route('professeur.dashboard');
            }

            if ($user->eleve()->exists()) {
                return redirect()->route('bulletin.index');
            }

            // Par défaut si aucun rôle
            return redirect('/');
        }

        // Si le compte existe mais inactif
        if (\App\Models\User::where('email', $request->email)->where('is_active', false)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Votre compte est en attente de validation.',
            ]);
        }

        // Échec d'authentification
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
