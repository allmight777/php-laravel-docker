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
                                <button type="button" class="auth-tab active" id="student-tab">
                                    <i class="fas fa-user-graduate me-2"></i> Élève
                                </button>
                                <button type="button" class="auth-tab" id="teacher-tab">
                                    <i class="fas fa-user-tie me-2"></i> Professeur
                                </button>
                            </div>

                            <form id="register-form" method="POST" action="{{ route('register') }}">
                                @csrf
                                <input type="hidden" name="user_type" id="user_type" value="student">

                                <!-- Champs Communs -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nom" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="prenom" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="telephone" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Photo</label>
                                        <input type="file" class="form-control" name="photo" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="mot_de_passe" required
                                            minlength="8">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirmation <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="mot_de_passe_confirmation"
                                            required>
                                    </div>

                                    <!-- Champs Étudiant -->
                                    <div id="student-fields">
                                        <div class="col-12">
                                            <label class="form-label">Date de naissance <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date_naissance" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Classe <span class="text-danger">*</span></label>
                                                <select class="form-select" name="classe_id" required>
                                                    @foreach ($classes as $classe)
                                                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Année académique <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="annee_academique_id" required>
                                                    @foreach ($annees as $annee)
                                                        <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Champs Professeur -->
                                    <div id="teacher-fields" style="display: none;">
                                        <div class="col-12">
                                            <label class="form-label">Date de naissance <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date_naissance">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Numéro WhatsApp</label>
                                            <input type="tel" class="form-control" name="numero_whatsapp">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Classes enseignées <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="classes[]" multiple style="height: 150px;">
                                                @foreach ($classes as $classe)
                                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélection
                                                multiple</small>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                Je certifie que les informations fournies sont exactes et j'accepte les
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#termsModal">conditions d'utilisation</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-def w-100 py-3">
                                            <i class="fas fa-paper-plane me-2"></i> Finaliser l'Inscription
                                        </button>
                                    </div>

                                    <div class="col-12 text-center mt-3">
                                        <p>Déjà inscrit? <a href="{{ route('login') }}"
                                                class="text-decoration-underline">Connectez-vous ici</a></p>
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
            const form = document.getElementById('register-form');

            // Fonction pour activer les champs étudiants
            function activateStudentFields() {
                // Activation visuelle
                studentTab.classList.add('active');
                teacherTab.classList.remove('active');

                // Affichage des champs
                studentFields.style.display = 'block';
                teacherFields.style.display = 'none';

                // Mise à jour du type d'utilisateur
                userTypeField.value = 'student';

                // Gestion des required
                form.querySelector('[name="classe_id"]').required = true;
                form.querySelector('[name="annee_academique_id"]').required = true;
                form.querySelector('[name="date_naissance"]').required = true;

                // Désactiver les required des champs profs
                form.querySelector('[name="classes[]"]').required = false;
            }

            // Fonction pour activer les champs professeurs
            function activateTeacherFields() {
                // Activation visuelle
                teacherTab.classList.add('active');
                studentTab.classList.remove('active');

                // Affichage des champs
                teacherFields.style.display = 'block';
                studentFields.style.display = 'none';

                // Mise à jour du type d'utilisateur
                userTypeField.value = 'teacher';

                // Gestion des required
                form.querySelector('[name="classes[]"]').required = true;
                form.querySelector('[name="date_naissance"]').required = true;

                // Désactiver les required des champs étudiants
                form.querySelector('[name="classe_id"]').required = false;
                form.querySelector('[name="annee_academique_id"]').required = false;
            }

            // Gestion du clic sur l'onglet Étudiant
            studentTab.addEventListener('click', activateStudentFields);

            // Gestion du clic sur l'onglet Professeur
            teacherTab.addEventListener('click', activateTeacherFields);

            // Validation avant soumission
            form.addEventListener('submit', function(e) {
                if (!document.getElementById('terms').checked) {
                    e.preventDefault();
                    alert('Veuillez accepter les conditions d\'utilisation');
                    return false;
                }

                // Validation supplémentaire selon le type d'utilisateur
                if (userTypeField.value === 'student') {
                    if (!form.querySelector('[name="classe_id"]').value ||
                        !form.querySelector('[name="annee_academique_id"]').value) {
                        e.preventDefault();
                        alert('Veuillez remplir tous les champs obligatoires pour les étudiants');
                        return false;
                    }
                } else {
                    if (!form.querySelector('[name="classes[]"]').selectedOptions.length) {
                        e.preventDefault();
                        alert('Veuillez sélectionner au moins une classe pour les professeurs');
                        return false;
                    }
                }
            });

            // Activer les champs étudiants par défaut
            activateStudentFields();
        });
    </script>
@endsection
