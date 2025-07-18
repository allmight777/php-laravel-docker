<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // Add this method to handle mobile requests better
    protected function sendResetResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            return response()->json(['status' => trans($response)]);
        }

        return redirect($this->redirectPath())
            ->with('status', trans($response))
            ->withCookie(cookie()->forever('password_reset', true));
}
}
