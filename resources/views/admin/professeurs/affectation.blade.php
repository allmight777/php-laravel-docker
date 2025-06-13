@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3>Affecter {{ $professeur->name }} à des classes et matières</h3>

        <form action="{{ route('admin.professeurs.affectation.store', $professeur->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="annee_scolaire">Année scolaire</label>
                <select name="annee_scolaire_id" class="form-select" required>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                    @endforeach
                </select>
            </div>

            @foreach ($classes as $classe)
                <div class="card mb-3">
                    <div class="card-header">
                        {{ $classe->nom }}
                    </div>
                    <div class="card-body">
                        @php
                            $matieresDisponibles = $classe->matieres->filter(function ($matiere) use ($professeur) {
                                return !$matiere->affectations->contains('professeur_id', $professeur->id);
                            });
                        @endphp

                        @if ($matieresDisponibles->count())
                            @foreach ($matieresDisponibles as $matiere)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="affectations[]"
                                           value="{{ $classe->id }}-{{ $matiere->id }}" id="aff_{{ $classe->id }}_{{ $matiere->id }}">
                                    <label class="form-check-label text-danger" for="aff_{{ $classe->id }}_{{ $matiere->id }}">
                                        {{ $matiere->nom }}
                                    </label>
                                    <label class="form-check-label text-dark">
                                        : {{ $matiere->code }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p id="text-muted">Aucune matière disponible pour cette classe.</p>
                        @endif
                    </div>
                </div>
            @endforeach

            <button class="btn btn-success mt-3">Enregistrer l’affectation</button>
        </form>
    </div>

    <style>
        #text-muted{
            color:rgb(0, 102, 255);
        }

        .card-body:hover{
            background-color: rgb(241, 218, 218);
        }
    </style>
@endsection
