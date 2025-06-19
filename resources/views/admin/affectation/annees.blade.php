@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4 text-primary fw-bold">Choisir une année scolaire</h3>

        <div class="row g-3">
            @foreach ($annees as $annee)
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('admin.affectation.classes', $annee->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100 hover-scale">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h5 class="card-title text-center mb-0 text-dark">{{ $annee->libelle }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .hover-scale {
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection
