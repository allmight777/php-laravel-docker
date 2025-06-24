@extends('layouts.admin')

@section('content')
    <div class="container mt-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <h3>Affecter {{ $professeur->name }} à des classes et matières</h3>

        <form action="{{ route('admin.professeurs.affectation.store', $professeur->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="annee_scolaire">Année scolaire</label>
                <select name="annee_scolaire_id" class="form-select" id="annee-selector" required>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                    @endforeach
                </select>
            </div>

            @foreach ($classes as $classe)
                <div class="card mb-3">
                    <div class="card-header">
                        {{ $classe->nom }} @if ($classe->serie)
                            ({{ $classe->serie }})
                        @endif
                    </div>
                    <div class="card-body">
                        @php
                            // Utilisez pour éliminer les doublon
                            $matieresUniques = $classe->matieres->unique('id');
                        @endphp

                        @if ($matieresUniques->count())
                            @foreach ($matieresUniques as $matiere)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="affectations[]"
                                        value="{{ $classe->id }}-{{ $matiere->id }}"
                                        id="aff_{{ $classe->id }}_{{ $matiere->id }}">
                                    <label class="form-check-label" for="aff_{{ $classe->id }}_{{ $matiere->id }}">
                                        {{ $matiere->nom }} ({{ $matiere->code }})
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Aucune matière disponible pour cette classe.</p>
                        @endif
                    </div>
                </div>
            @endforeach

            <button class="btn btn-success mt-3">Enregistrer l'affectation</button>
            <a href="{{ route('admin.professeurs.affectation.edit', $professeur->id) }}"
                class="btn btn-primary mt-3">Modifier les affectations existantes</a>
        </form>
    </div>
@endsection
