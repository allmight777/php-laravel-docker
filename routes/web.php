<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PeriodeAcademiqueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('index');

// Authentification
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Routes Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Gestion des utilisateurs
        Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/user/{id}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/user/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('users.deactivate');
        Route::post('/user/{id}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');

        Route::get('/users', [AdminController::class, 'listUsers'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Approbations
        Route::get('/approvals', [AdminController::class, 'pendingUsers'])->name('users.pending');

        // Liste des utilisateurs actifs
        Route::get('/active-users', [AdminController::class, 'activeUsers'])->name('users.active');

        // Années scolaires
        Route::get('annees-scolaires', [AdminController::class, 'anneesScolaires'])->name('annees.index');
        Route::get('annees-scolaires/create', [AdminController::class, 'createAnnee'])->name('annees.create');
        Route::post('annees-scolaires', [AdminController::class, 'storeAnnee'])->name('annees.store');
        Route::get('annees-scolaires/{id}/edit', [AdminController::class, 'editAnnee'])->name('annees.edit');
        Route::put('annees-scolaires/{id}', [AdminController::class, 'updateAnnee'])->name('annees.update');
        Route::delete('annees-scolaires/{id}', [AdminController::class, 'destroyAnnee'])->name('annees.delete');

        // Périodes académiques
        Route::get('periodes', [PeriodeAcademiqueController::class, 'index'])->name('periodes.index');
        Route::get('periodes/create', [PeriodeAcademiqueController::class, 'create'])->name('periodes.create');
        Route::post('periodes', [PeriodeAcademiqueController::class, 'store'])->name('periodes.store');
        Route::get('periodes/{id}/edit', [PeriodeAcademiqueController::class, 'edit'])->name('periodes.edit');
        Route::put('periodes/{id}', [PeriodeAcademiqueController::class, 'update'])->name('periodes.update');
        Route::delete('periodes/{id}', [PeriodeAcademiqueController::class, 'destroy'])->name('periodes.destroy');

        // Affectation classes/matières par année scolaire
        Route::get('professeurs/{professeur}/affectation', [AdminController::class, 'affectation'])->name('professeurs.affectation');
        Route::post('professeurs/{professeur}/affectation', [AdminController::class, 'storeAffectation'])->name('professeurs.affectation.store');
        Route::get('/professeurs/{id}/edit-affectation', [AdminController::class, 'editAffectation'])->name('professeurs.affectation.edit');
        Route::put('/professeurs/{id}/update-affectation', [AdminController::class, 'updateAffectation'])->name('professeurs.affectation.update');

        // Afficher les années scolaires pour affectation
        Route::get('affectations/annees', [AdminController::class, 'affectationAnnees'])->name('affectation.annees');

        // Afficher les classes pour une année donnée
        Route::get('affectations/{annee}/classes', [AdminController::class, 'affectationClasses'])->name('affectation.classes');

        // Afficher les élèves actifs non affectés pour la classe/année sélectionnées
        Route::get('affectations/{annee}/{classe}/eleves', [AdminController::class, 'affectationEleves'])->name('affectation.eleves');

        // soumission d’affectation
        Route::post('affectations/assign', [AdminController::class, 'assignerElevesClasse'])->name('affectation.assigner');

    });

    // Classes et Professeurs
    Route::resource('classes', ClasseController::class);
    Route::resource('professeurs', ProfesseurController::class);

    // Affecter des classes à un professeur
    Route::post('/professeurs/{professeur}/affecter-classes', [ProfesseurController::class, 'affecterClasses'])->name('professeurs.affecter-classes');

    // Matières d'une classe
    Route::get('/api/classes/{classe}/matieres', [ClasseController::class, 'getMatieres'])->name('classes.matieres');

    // Routes Professeur
    Route::prefix('professeur')->name('professeur.')->group(function () {
        Route::get('/dashboard', [ProfesseurController::class, 'dashboard'])->name('dashboard');
        Route::get('/classes/{anneeId}', [ProfesseurController::class, 'mesClasses'])->name('classes');
        Route::get('/classe/{anneeId}/{classeId}/eleves', [ProfesseurController::class, 'elevesParClasse'])->name('classe.eleves');
        Route::get('/statistiques/{anneeId}/{classeId}', [ProfesseurController::class, 'showStatistics'])->name('statistiques.show');
        Route::post('/notes/enregistrer', [ProfesseurController::class, 'saisirNotes'])->name('notes.enregistrer');
    });

    // Routes pour la modification du profil utilisateur
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::get('/profile/editadmin', [UserController::class, 'admineditProfile'])->name('profile.admin.edit');
    Route::put('/profile/updateadmin', [UserController::class, 'adminupdateProfile'])->name('profile.admin.update');

});

// Réinitialisation des mots de passe , pour avoir tout les options auth
Auth::routes();
