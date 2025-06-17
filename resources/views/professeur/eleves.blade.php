@extends('layouts.app')

@section('content')
    <div class="professor-eleves">
        <div class="container py-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Élèves de {{ $classe->nom }}</h3>
                    <span class="badge bg-light text-dark">{{ $eleves->count() }} élèves</span>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                    <form id="notesForm" action="{{ route('professeur.notes.enregistrer') }}" method="POST">
                        @csrf
                        <input type="hidden" name="classe_id" value="{{ $classe->id }}">
                        <input type="hidden" name="matiere_id" value="{{ $matiere->id }}">

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Interro 1</th>
                                        <th>Interro 2</th>
                                        <th>Interro 3</th>
                                        <th>Devoir 1</th>
                                        <th>Devoir 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $eleve)
                                        <tr>
                                            <td>{{ $eleve->user->nom }}</td>
                                            <td>{{ $eleve->user->prenom }}</td>
                                            <td>
                                                <input type="number" class="form-control note-input"
                                                    name="notes[{{ $eleve->id }}][interro1]" min="0"
                                                    max="20" step="0.25" oninput="validateNote(this)">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control note-input"
                                                    name="notes[{{ $eleve->id }}][interro2]" min="0"
                                                    max="20" step="0.25" oninput="validateNote(this)">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control note-input"
                                                    name="notes[{{ $eleve->id }}][interro3]" min="0"
                                                    max="20" step="0.25" oninput="validateNote(this)">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control note-input"
                                                    name="notes[{{ $eleve->id }}][devoir1]" min="0"
                                                    max="20" step="0.25" oninput="validateNote(this)">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control note-input"
                                                    name="notes[{{ $eleve->id }}][devoir2]" min="0"
                                                    max="20" step="0.25" oninput="validateNote(this)">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Enregistrer les notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateNote(input) {
            if (parseFloat(input.value) > 20) {
                input.value = 20;
                alert('La note maximale est 20');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('notesForm');

            form.addEventListener('submit', function(e) {
                const rows = document.querySelectorAll('tbody tr');
                let isValid = true;

                rows.forEach(row => {
                    const inputs = row.querySelectorAll('.note-input');
                    inputs.forEach(input => {
                        input.classList.remove('is-invalid');
                    });

                    let allFilled = true;

                    inputs.forEach(input => {
                        if (input.value === '') {
                            allFilled = false;
                            input.classList.add('is-invalid');
                        }
                    });

                    if (!allFilled) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert("Veuillez remplir toutes les notes pour chaque élève avant de soumettre.");
                }
            });
        });
    </script>


    <style>
        .professor-eleves {
            position: relative;
            height: 800px;
            background: url({{ asset('images/image_3.png') }}) no-repeat center top;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
            padding-top: 50px;
        }

        .note-input {
            max-width: 80px;
            text-align: center;
        }

        .table th {
            white-space: nowrap;
        }

        .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>
@endsection
