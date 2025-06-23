@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Tableau de bord administrateur</h2>
        <h5 class="mb-4 text-dark">Validation - Supression - Désactivation</h5>

        <div class="row mb-4 ">
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-clock card-icon"></i>
                        <h5 class="card-title">Demandes en attente</h5>
                        <h3 class="card-text">{{ $counts['pending'] }}</h3>
                        <a href="{{ route('admin.users.pending') }}" class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title">Utilisateurs actifs</h5>
                        <h3 class="card-text">{{ $counts['active'] }}</h3>
                        <a href="{{ route('admin.users.active') }}" class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <br>
            <h5 class="mb-4 text-dark">Ajouter-Année-Période</h5>

            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-black text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar card-icon"></i>
                        <h5 class="card-title">Ajouter/Modifer année</h5>
                        <h3 class="card-text"></h3>
                        <a href="{{ route('admin.annees.index') }}" class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>

        </div>

             <div class="card mb-4">
            <br>
            <h5 class="mb-4 text-dark">Affectation-Elèves-Professeurs</h5>

            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-compass card-icon"></i>
                        <h5 class="card-title">Affectation professeurs</h5>
                        <h3 class="card-text"></h3>
                        <a href="{{ route('professeurs.index') }}" class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mb-4">
            <br>
            <h5 class="mb-4 text-dark">Modifier comptes</h5>

            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-edit card-icon"></i>
                        <h5 class="card-title">Modifier les comptes utilisateurs</h5>
                        <h3 class="card-text"></h3>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>

        </div>

         <div class="card mb-4">
            <br>
            <h5 class="mb-4 text-dark">Resultats fin d'annee</h5>

            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-edit card-icon"></i>
                        <h5 class="card-title">Migrations</h5>
                        <h3 class="card-text"></h3>
                        <a href="{{ route('admin.classes') }} " class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>

        </div>
         <div class="card mb-4">
            <br>
            <h5 class="mb-4 text-dark">Resultats fin d'annee</h5>

            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-edit card-icon"></i>
                        <h5 class="card-title">Migrations</h5>
                        <h3 class="card-text"></h3>
                       <a href="{{ route('admin.resultats') }}" class="btn btn-light btn-sm">Voir</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
