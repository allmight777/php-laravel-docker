<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfesseurController extends Controller
{


    public function affecterClasses(Request $request, Professeur $professeur)
{
    $request->validate([
        'classes' => 'required|array',
        'classes.*' => 'exists:classes,id'
    ]);

    $professeur->classes()->sync($request->classes);

    return back()->with('success', 'Classes affectées avec succès');
}
}
