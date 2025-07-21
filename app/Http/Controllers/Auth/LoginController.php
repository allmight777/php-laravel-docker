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


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
