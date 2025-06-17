<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin {{ $annee }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-bulletin {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .matiere-card {
            border-left: 4px solid #0d6efd;
            margin-bottom: 20px;
        }
        .moyenne-box {
            background-color: #e9ecef;
            border-radius: 5px;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="header-bulletin">
            <div class="row align-items-center">
                <div class="col">
                    <h2>Bulletin Scolaire - Année {{ $annee }}</h2>
                    <h3>{{ $eleve->prenom }} {{ $eleve->nom }}</h3>
                    <p class="mb-0">Classe: {{ $eleve->classe }}</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('bulletin.index') }}" class="btn btn-outline-primary">
                        ← Retour aux années
                    </a>
                </div>
                <div class="col-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>

        @foreach($notesParMatiere as $matiere => $notes)
            <div class="card matiere-card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $matiere }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Note</th>
                                    <th>Date</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notes as $note)
                                    <tr>
                                        <td>{{ $note->valeur }}/20</td>
                                        <td>{{ $note->periode->format('d/m/Y') }}</td>
                                        <td>{{ $note->type_evaluation }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-info">
                                    <td colspan="3" class="text-end">
                                        <strong>Moyenne: {{ round($notes->avg('valeur'), 2) }}/20</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="moyenne-box text-center mt-4">
            <h4 class="mb-0">Moyenne Générale: {{ $moyenneGenerale }}/20</h4>
        </div>
    </div>
</body>
</html>