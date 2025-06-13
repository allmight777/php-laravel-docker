<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\ClasseMatiereProfesseur;
use App\Models\Eleve;
use App\Models\Professeur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $rules = [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telephone' => ['required', 'string', 'max:20'],
            'date_de_naissance' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'terms' => ['required'],
            'user_type' => ['required', 'in:eleve,professeur'],
        ];

        if (isset($data['user_type']) && $data['user_type'] === 'eleve') {
            $rules['classe_id'] = ['required', 'exists:classes,id'];
            $rules['annee_academique_id'] = ['required', 'exists:annee_academique,id'];
        } elseif (isset($data['user_type']) && $data['user_type'] === 'professeur') {
            $rules['prof_classes'] = ['required', 'array'];
            $rules['prof_classes.*'] = ['exists:classes,id'];

            $rules['prof_matieres'] = ['required', 'array'];
            foreach ($data['prof_matieres'] ?? [] as $classeId => $matieres) {
                $rules["prof_matieres.$classeId"] = ['required', 'array'];
                $rules["prof_matieres.$classeId.*"] = ['exists:matieres,id'];
            }
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $photoPath = null;
        if (isset($data['photo']) && $data['photo']) {
            if (is_object($data['photo']) && method_exists($data['photo'], 'store')) {
                $photoPath = $data['photo']->store('avatars', 'public');
            }
        }

        if (! $photoPath) {
            $photoPath = 'avatars/default.png';
        }

        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'date_de_naissance' => $data['date_de_naissance'],
            'password' => Hash::make($data['password']),
            'photo' => $photoPath,
            'is_active' => false,
        ]);

        if ($data['user_type'] === 'eleve') {
            Eleve::create([
                'user_id' => $user->id,
                'classe_id' => $data['classe_id'],
                'annee_academique_id' => $data['annee_academique_id'],
            ]);
        } elseif ($data['user_type'] === 'professeur') {
            $professeur = Professeur::create([
                'user_id' => $user->id,
            ]);

            foreach ($data['prof_classes'] as $index => $classeId) {

                if (isset($data['prof_matieres'][$index]) && is_array($data['prof_matieres'][$index])) {
                    foreach ($data['prof_matieres'][$index] as $matiereId) {

                        ClasseMatiereProfesseur::create([
                            'classe_id' => $classeId,
                            'matiere_id' => $matiereId,
                            'professeur_id' => $professeur->id,
                            'coefficient' => 1,
                        ]);
                    }
                }
            }
        }

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    public function showRegistrationForm()
    {
        $classes = Classe::all();
        $annees = AnneeAcademique::all();

        $matieresParClasse = [];

        foreach ($classes as $classe) {

            $matieresParClasse[$classe->id] = ClasseMatiereProfesseur::where('classe_id', $classe->id)
                ->whereNull('professeur_id')
                ->with('matiere')
                ->get()
                ->map(function ($cmp) {
                    return [
                        'id' => $cmp->matiere->id,
                        'nom' => $cmp->matiere->nom,
                        'code' => $cmp->matiere->code,
                    ];
                })->toArray();
        }

        return view('auth.register', compact('classes', 'annees', 'matieresParClasse'));
    }

    protected function registered(Request $request, $user)
    {
        return redirect($this->redirectPath())
            ->with('success', 'Votre inscription a été soumise. Un administrateur validera votre compte sous 48h.');
    }

    protected function redirectPath()
    {
        return $this->redirectTo ?? '/login';
    }

  
}
