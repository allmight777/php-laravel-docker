@extends('layouts.app')

@section('content')
    <!-- Login Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="auth-card animate__animated animate__fadeInUp">
                        <div class="auth-header">
                            <h3><i class="fas fa-sign-in-alt me-2"></i> Connexion</h3>
                        </div>
                        <div class="auth-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                </div>
                                <button type="submit" class="btn btn-def w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                                </button>
                                <div class="text-center mt-3">
                                    <p>Pas encore de compte? <a href="{{ route('register') }}">Inscrivez-vous ici</a></p>
                                </div>
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a href="">Mot de passe oublié?</a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
