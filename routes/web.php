<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\register;
use Illuminate\Support\Facades\Route;


// Route-acceuil
Route::get('/', [register::class, 'index'])->name('index');

// Register-Route
Route::get('/register', [register::class, 'register'])->name('register');
Route::post('/register', [register::class, 'registerValidation'])->name('register');

// Login-Route
Route::get('/login', [register::class, 'login'])->name('login');

Route::resource('classes', ClasseController::class);
Route::resource('professeurs', ProfesseurController::class);

// Route pour gérer l'affectation des classes aux professeurs
Route::post('/professeurs/{professeur}/affecter-classes', [ProfesseurController::class, 'affecterClasses'])
    ->name('professeurs.affecter-classes');

use App\Models\Classe;

// Récupérer toutes les classes
$classes = Classe::all();

// Pagination
$classes = Classe::paginate(10);

// Filtrer par niveau
$classesSecondaires = Classe::where('niveau', 'secondaire')->get();

// Relations
$classesWithMatieres = Classe::with('matieres')->get();
