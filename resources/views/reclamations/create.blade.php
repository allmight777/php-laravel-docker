@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Nouvelle Réclamation</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('reclamations.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="note_id" class="form-label">Note concernée</label>
                    <select class="form-select" id="note_id" name="note_id" required>
                        @foreach($notes as $note)
                            <option value="{{ $note->id }}">
                                {{ $note->matiere->nom }} - {{ $note->valeur }}/20
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer la réclamation</button>
            </form>
        </div>
    </div>
</div>
@endsection