@extends('layouts.app')

@section('content')
    <!-- Registration Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="auth-card animate__animated animate__fadeInUp">
                        <div class="auth-header">
                            <h3><i class="fas fa-user-plus me-2"></i> Créer un compte</h3>
                            <p class="mb-0">Sélectionnez votre profil pour commencer l'inscription</p>
                        </div>

                        <div class="auth-body">
                            <div class="d-flex justify-content-center mb-4">
                                <button type="button"
                                    class="auth-tab {{ old('user_type', 'eleve') == 'eleve' ? 'active' : '' }}"
                                    id="student-tab">
                                    <i class="fas fa-user-graduate me-2"></i> Élève
                                </button>
                                <button type="button"
                                    class="auth-tab {{ old('user_type') == 'professeur' ? 'active' : '' }}"
                                    id="teacher-tab">
                                    <i class="fas fa-user-tie me-2"></i> Professeur
                                </button>
                            </div>


                            <form id="register-form" method="POST" action="{{ route('register') }}"
                                enctype="multipart/form-data">

                                @csrf
                                <input type="hidden" name="user_type" id="user_type" 
                                    value="{{ old('user_type', 'eleve') }}">

                                <!-- Champs Communs -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label">Nom <span
                                                class="text-danger">*</span></label>
                                        <input id="nom" type="text"
                                            class="form-control @error('nom') is-invalid @enderror" name="nom"
                                            value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label">Prénom <span
                                                class="text-danger">*</span></label>
                                        <input id="prenom" type="text"
                                            class="form-control @error('prenom') is-invalid @enderror" name="prenom"
                                            value="{{ old('prenom') }}" required>
                                        @error('prenom')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">Téléphone <span
                                                class="text-danger">*</span></label>
                                        <input id="telephone" type="text"
                                            class="form-control @error('telephone') is-invalid @enderror" name="telephone"
                                            value="{{ old('telephone') }}" required>
                                        @error('telephone')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="date_de_naissance" class="form-label">Date de naissance <span
                                                class="text-danger">*</span></label>
                                        <input id="date_de_naissance" type="date"
                                            class="form-control @error('date_de_naissance') is-invalid @enderror"
                                            name="date_de_naissance" value="{{ old('date_de_naissance') }}" required>
                                        @error('date_de_naissance')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Mot de passe <span
                                                class="text-danger">*</span></label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required minlength="8">
                                        <small class="text-muted">Minimum 8 caractères</small>
                                        @error('password')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password-confirm" class="form-label">Confirmation <span
                                                class="text-danger">*</span></label>
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required>
                                    </div>

                                    <!-- Champs Étudiant -->
                                    

                                    <div class="col-md-12">
                                        <label for="photo" class="form-label">Photo de profil</label>
                                        <input id="photo" type="file"
                                            class="form-control @error('photo') is-invalid @enderror" name="photo"
                                            accept="image/jpeg,image/png,image/jpg">
                                        <small class="text-muted">Formats acceptés : JPEG, PNG. Max 2 Mo.</small>
                                        @error('photo')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <!-- Champs Professeur -->
                                    <div id="teacher-fields"
                                        style="{{ old('user_type') == 'professeur' ? '' : 'display: none;' }}">
                                    </div>

                                    <!-- Conditions -->
                                    <div class="col-12 mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                                type="checkbox" id="terms" name="terms" required
                                                {{ old('terms') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="terms">
                                                Je certifie que les informations fournies sont exactes et j'accepte les
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#termsModal">conditions d'utilisation</a>
                                            </label>
                                            @error('terms')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-def w-100 py-3">
                                            <i class="fas fa-paper-plane me-2"></i> Finaliser l'Inscription
                                        </button>
                                    </div>

                                    <div class="col-12 text-center mt-3">
                                        <p>Déjà inscrit ? <a href="{{ route('login') }}" class="text-decoration-underline">Connectez-vous ici</a></p>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Conditions d'utilisation -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-def text-white">
                    <h5 class="modal-title">Conditions d'Utilisation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Acceptation des conditions</h6>
                    <p>En utilisant cette plateforme, vous acceptez de vous conformer aux règlements en vigueur...</p>

                    <h6>2. Confidentialité des données</h6>
                    <p>Toutes les informations fournies sont protégées selon la loi sur la protection des données...</p>

                    <h6>3. Responsabilités</h6>
                    <p>Chaque utilisateur est responsable de la confidentialité de ses identifiants...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-def" data-bs-dismiss="modal">J'ai compris</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentTab = document.getElementById('student-tab');
            const teacherTab = document.getElementById('teacher-tab');
            const studentFields = document.getElementById('student-fields');
            const teacherFields = document.getElementById('teacher-fields');
            const userTypeField = document.getElementById('user_type');



            studentTab.addEventListener('click', function() {
                studentTab.classList.add('active');
                teacherTab.classList.remove('active');
                studentFields.style.display = 'block';
                teacherFields.style.display = 'none';
                userTypeField.value = 'eleve';
            });

            teacherTab.addEventListener('click', function() {
                teacherTab.classList.add('active');
                studentTab.classList.remove('active');
                studentFields.style.display = 'none';
                teacherFields.style.display = 'block';
                userTypeField.value = 'professeur';
            });


            const classesContainer = document.getElementById('classes-container');
            const addClassBtn = document.getElementById('add-class-btn');


          

            @if (old('user_type') == 'professeur')
                teacherTab.click();
            @endif
        });
    </script>
@endsection
