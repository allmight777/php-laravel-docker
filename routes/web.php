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
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Gestion des utilisateurs
        Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::post('/user/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
        Route::post('/user/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
        Route::post('/user/{id}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');

        // Approbations
        Route::get('/approvals', [AdminController::class, 'pendingUsers'])->name('admin.users.pending');

        // Liste des utilisateurs actifs
        Route::get('/active-users', [AdminController::class, 'activeUsers'])->name('admin.users.active');
    });

    // Ressources : Classes et Professeurs
    Route::resource('classes', ClasseController::class);
    Route::resource('professeurs', ProfesseurController::class);

    // classes à un professeur
    Route::post('/professeurs/{professeur}/affecter-classes', [ProfesseurController::class, 'affecterClasses'])->name('professeurs.affecter-classes');

    // matières d'une classe
    Route::get('/api/classes/{classe}/matieres', [ClasseController::class, 'getMatieres'])->name('classes.matieres');

    // Routes Professeur
    Route::prefix('professeur')->group(function () {
        Route::get('/dashboard', [ProfesseurController::class, 'dashboard'])->name('professeur.dashboard');
        Route::get('/classes', [ProfesseurController::class, 'mesClasses'])->name('professeur.classes');
        Route::get('/classe/{classe}/eleves', [ProfesseurController::class, 'elevesParClasse'])->name('professeur.classe.eleves');
        Route::post('/notes/enregistrer', [ProfesseurController::class, 'enregistrerNotes'])->name('professeur.notes.enregistrer');
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

