@extends('layouts.app')

@section('content')

    <div class="professor-dashboard">

        <div class="professor-hero">
            <br><br><br><br><br><br><br><br><br>
            <div class="hero-overlay"></div>
            <div class="container">
                <div class="hero-content text-center text-white">
                    <h1 class="display-4 animate__animated animate__fadeInDown">Espace Professeur</h1>
                    <p class="lead animate__animated animate__fadeInUp">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card dashboard-card animate__animated animate__fadeInLeft">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                                <h3>Mes Classes</h3>
                                <p>Gérer vos classes et matières</p>
                                <a href="{{ route('professeur.classes') }}" class="btn btn-primary">Accéder</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card animate__animated animate__fadeInUp">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                <h3>Notes</h3>
                                <p>Saisir et consulter les notes</p>
                                <a href="#" class="btn btn-primary">Accéder</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .professor-hero {
            position: relative;
            height: 900px;
            background:  url('../images/image_5.png') no-repeat center top;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay {
            position: absolute;
            top: ;
            left: 0;
            width: 100%;
            height: 100%;

        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .dashboard-card i {
            color: #3490dc;
        }
    </style>
@endsection
