@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Tableau de bord administrateur</h2>
        <h5 class="mb-4 text-dark">Validation - Supression - Désactivation</h5>

        <div class="row mb-4 ">
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card bg-primary text-white">
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
            <h5 class="mb-4 text-dark">Autes</h5>

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
    </div>
@endsection
