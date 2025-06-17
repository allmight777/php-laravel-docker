@extends('layouts.app')

@section('content')
    <div class="professor-dashboard">
        <div class="professor-hero">
            <div class="hero-overlay"></div>
            <div class="container">
                <div class="hero-content text-center text-white animate__animated animate__fadeIn">
                    <h1 class="display-4">Espace Professeur</h1>
                    <p class="lead">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                </div>
            </div>
        </div>

        <div class="container py-5 animate__animated animate__fadeInUp">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3><i class="fas fa-calendar-alt me-2"></i> Choisir une année académique</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($annees as $annee)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar fa-3x text-primary mb-3"></i>
                                        <h4>{{ $annee->libelle }}</h4>
                                        <p class="text-muted">Gérer vos classes et notes</p>
                                        <a href="{{ route('professeur.classes', $annee->id) }}"
                                            class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-arrow-right me-2"></i> Sélectionner
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .professor-dashboard {
            min-height: 100vh;
        }

        .professor-hero {
            position: relative;
            height: 400px;
            background-size: cover;
            display: flex;
            align-items: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding-top: 80px;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
