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
        $this->middleware('guest')->except('logout');
    }

public function connexion(Request $request)
{
    // Validation minimale
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Tentative de connexion avec condition is_active = true
    $credentials = $request->only('email', 'password');
    $credentials['is_active'] = true;

    if (auth()->attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = auth()->user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->professeur) {
            return redirect()->route('professeur.dashboard');
        } elseif ($user->eleve) {
            return redirect()->route('bulletin.index');
        }

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
