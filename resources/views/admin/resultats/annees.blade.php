@extends('layouts.admin')

@section('content')
    <h2>Résultats fin d'année - Sélectionnez une année académique</h2>
    
    <div class="list-group">
        @foreach($annees as $annee)
            <a href="{{ route('admin.resultats.classes', $annee->id) }}" class="list-group-item list-group-item-action">
                {{ $annee->libelle }}
            </a>
        @endforeach
    </div>
@endsection