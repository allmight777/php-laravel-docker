@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3>Modifier les affectations de {{ $professeur->name }}</h3>

        <form action="{{ route('admin.professeurs.affectation.update', $professeur->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="annee_scolaire">Année scolaire</label>
                <select name="annee_academique_id" class="form-select" id="annee-selector" required>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}">{{ $annee->libelle }}</option>
                    @endforeach
                </select>
            </div>

            <div id="affectations-container">
                @foreach ($affectations as $anneeId => $affectationGroup)
                    <div class="annee-affectations" data-annee="{{ $anneeId }}" style="display: {{ $loop->first ? 'block' : 'none' }}">
                        @foreach ($affectationGroup->groupBy('classe_id') as $classeId => $classeAffectations)
                            <div class="card mb-3">
                                <div class="card-header">
                                    {{ $classeAffectations->first()->classe->nom }}
                                </div>
                                <div class="card-body">
                                    @foreach ($classeAffectations as $affectation)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="affectations[]"
                                                   value="{{ $affectation->classe_id }}-{{ $affectation->matiere_id }}"
                                                   id="aff_{{ $affectation->classe_id }}_{{ $affectation->matiere_id }}"
                                                   checked>
                                            <label class="form-check-label" for="aff_{{ $affectation->classe_id }}_{{ $affectation->matiere_id }}">
                                                {{ $affectation->matiere->nom }} ({{ $affectation->matiere->code }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <button class="btn btn-success mt-3">Mettre à jour les affectations</button>
        </form>
    </div>

    <script>
        document.getElementById('annee-selector').addEventListener('change', function() {
            const selectedAnnee = this.value;
            document.querySelectorAll('.annee-affectations').forEach(el => {
                el.style.display = el.dataset.annee === selectedAnnee ? 'block' : 'none';
            });
        });
    </script>
@endsection
