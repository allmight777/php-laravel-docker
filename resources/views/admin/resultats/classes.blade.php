@extends('layouts.admin')

@section('content')
    <h2>Résultats fin d'année - {{ $annee->libelle }} - Sélectionnez une classe</h2>
    
    <div class="list-group">
        @foreach($classes as $classe)
            <a href="{{ route('admin.resultats.eleves', ['anneeId' => $annee->id, 'classeId' => $classe->id]) }}" 
               class="list-group-item list-group-item-action">
                {{ $classe->nom }}
            </a>
        @endforeach
    </div>
@endsection