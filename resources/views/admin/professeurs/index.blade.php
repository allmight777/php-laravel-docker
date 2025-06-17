@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h3 class="text-dark mb-3">Liste des professeurs</h3>

        <table class="table table-bordered" id="usersTable">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Date de naissance</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <div class="input-group mb-3" style="max-width: 100%;">
                    <input type="text" class="form-control" placeholder="Rechercher..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </thead>

            <tbody>
                @foreach ($professeurs as $prof)
                    <tr>
                        <td>{{ $prof->user->nom }}</td>
                        <td>{{ $prof->user->prenom }}</td>
                        <td>{{ $prof->user->telephone }}</td>
                        <td>{{ $prof->user->date_de_naissance }}</td>
                        <td>{{ $prof->user->email }}</td>
                        <td>
                            <a href="{{ route('admin.professeurs.affectation', $prof->id) }}"
                                class="btn btn-primary btn-sm">Affecter classe/matière</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    </script>
@endsection
