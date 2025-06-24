@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Réclamations</h2>
    
    <div class="list-group">
        @foreach($reclamations as $reclamation)
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <h5>{{ $reclamation->eleve->user->prenom }} {{ $reclamation->eleve->user->nom }}</h5>
                    <span class="badge bg-{{ $reclamation->statut == 'en_attente' ? 'warning' : ($reclamation->statut == 'traitee' ? 'success' : 'danger') }}">
                        {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                    </span>
                </div>
                <p><strong>Matière:</strong> {{ $reclamation->matiere->nom }}</p>
                <p><strong>Note concernée:</strong> {{ $reclamation->note->valeur }}/20</p>
                <p><strong>Message:</strong> {{ $reclamation->message }}</p>
                
                @if($reclamation->statut == 'en_attente')
                    <form method="POST" action="{{ route('reclamations.traiter', $reclamation) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label>Réponse</label>
                            <textarea name="reponse" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="statut" value="traitee" class="btn btn-success">Accepter</button>
                        <button type="submit" name="statut" value="rejetee" class="btn btn-danger">Rejeter</button>
                    </form>
                @else
                    <p><strong>Réponse:</strong> {{ $reclamation->reponse }}</p>
                @endif
            </div>
        @endforeach
    </div>

    {{ $reclamations->links() }}
</div>
@endsection