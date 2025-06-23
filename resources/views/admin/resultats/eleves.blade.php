@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Résultats fin d'année - Classe {{ $classe->nom }} - Année {{ $annee->libelle }}</h3>
    
    <h4 class="mt-4">Élèves Admis (Moyenne ≥ 10)</h4>
    @if($elevesAdmis->isEmpty())
        <p>Aucun élève admis</p>
    @else
        <ul class="list-group mb-4">
            @foreach($elevesAdmis as $eleve)
                <li class="list-group-item">
                    {{ $eleve->user->prenom }} {{ $eleve->user->nom }} - 
                    <strong>Moyenne: {{ number_format($eleve->moyenne_generale, 2) }}/20</strong>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('migration.export.admis', [$annee->id, $classe->id]) }}" 
           class="btn btn-success mb-4">
            Télécharger PDF Admis
        </a>
    @endif

    <hr>

    <h4>Élèves Non Admis (Moyenne < 10)</h4>
    @if($elevesRefuses->isEmpty())
        <p>Aucun élève non admis</p>
    @else
        <ul class="list-group mb-4">
            @foreach($elevesRefuses as $eleve)
                <li class="list-group-item">
                    {{ $eleve->user->prenom }} {{ $eleve->user->nom }} - 
                    <strong>Moyenne: {{ number_format($eleve->moyenne_generale, 2) }}/20</strong>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('migration.export.refuses', [$annee->id, $classe->id]) }}" 
           class="btn btn-danger">
            Télécharger PDF Non Admis
        </a>
    @endif
</div>
@endsection