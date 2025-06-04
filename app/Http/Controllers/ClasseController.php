<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::all();
        return view('classes.create', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'niveau' => 'required|string|max:20',
            'serie' => 'nullable|string|max:50'
        ]);

        Classe::create($validated);

        return redirect()->route('classes.index')
                         ->with('success', 'Classe créée avec succès');
    }

}
