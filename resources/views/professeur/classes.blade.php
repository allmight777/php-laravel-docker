@extends('layouts.app')

@section('content')
    <div class="professor-classes">
        <div class="container py-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Mes Classes</h3>
                </div>

                <div class="card-body">
                    @if ($classes->isEmpty())
                        <div class="alert alert-info">Vous n'êtes affecté à aucune classe pour le moment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Classe</th>
                                        <th>Série</th>
                                        <th>Matière</th>
                                        <th>Coefficient</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $classe)
                                        <tr>
                                            <td>{{ $classe->classe->nom }}</td>
                                            <td>{{ $classe->classe->serie ?? 'N/A' }}</td>
                                            <td>{{ $classe->matiere->nom }}</td>
                                            <td>{{ $classe->coefficient }}</td>
                                            <td>
                                                <a href="{{ route('professeur.classe.eleves', $classe->classe->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-users"></i> Voir les élèves
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .professor-classes {
             background-color: #f8f9fa;
            position: relative;
            height: 600px;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
            padding-top: 50px;
        }

    </style>
@endsection
