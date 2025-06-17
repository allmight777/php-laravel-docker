<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\BulletinController;
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



        // Affectation classes/matières par année scolaire
        Route::get('professeurs/{professeur}/affectation', [AdminController::class, 'affectation'])->name('professeurs.affectation');
        Route::post('professeurs/{professeur}/affectation', [AdminController::class, 'storeAffectation'])->name('professeurs.affectation.store');
    });

    //Classes et Professeurs
    Route::resource('classes', ClasseController::class);
    Route::resource('professeurs', ProfesseurController::class);

    // Affecter des classes à un professeur
    Route::post('/professeurs/{professeur}/affecter-classes', [ProfesseurController::class, 'affecterClasses'])->name('professeurs.affecter-classes');

    // Matières d'une classe
    Route::get('/api/classes/{classe}/matieres', [ClasseController::class, 'getMatieres'])->name('classes.matieres');

    // Routes Professeur
    Route::prefix('professeur')->name('professeur.')->group(function () {
        Route::get('/dashboard', [ProfesseurController::class, 'dashboard'])->name('dashboard');
        Route::get('/classes', [ProfesseurController::class, 'mesClasses'])->name('classes');
        Route::get('/classe/{classe}/eleves', [ProfesseurController::class, 'elevesParClasse'])->name('classe.eleves');
        Route::post('/notes/enregistrer', [ProfesseurController::class, 'enregistrerNotes'])->name('notes.enregistrer');
    });
    //eleves
    Route::get('bulletin', [ProfesseurController::class, 'dashboar'])->name('bulletin.index');
    //Route-bouton telechargement
    //Route::get('/download-file', [DownloadController::class, 'download'])->name('download.file');
    Route::get('/download-file', function () {
    return Storage::download('public/files/file1.txt');
})->name('download.file');
});
//
//Route::get('/bulletin',[BulletinController])->name('bulletin');

//Réinstallisation des mots de passe 
Auth::routes();


// Réinitialisation des mots de passe
Auth::routes();
