<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirection
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
     * Validation
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {

        return $this->guard()->attempt(
            array_merge($this->credentials($request), ['is_active' => true]),
            $request->filled('remember')
        );
    }

    /**
     * Réponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        if (User::where('email', $request->email)
            ->where('is_active', false)
            ->exists()) {
            $errors = [$this->username() => 'Votre compte est en attente de validation.'];
        }

        throw ValidationException::withMessages($errors);
    }

    /**
     * Redirection
     */
    protected function authenticated(Request $request, $user)
    {

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->professeur) {
            return redirect()->route('professeur.dashboard');
        }
        
        // Par défaut pour les élèves
        return redirect()->intended($this->redirectPath());

    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect('/');
    }
}
