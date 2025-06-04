<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolConnect - Plateforme Éducative du Ministère de la Défense</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- lien css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-def fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">
                <i class="fas fa-shield-alt me-2"></i>SchoolConnect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('index') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer-def">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="{{ route('index') }}" class="footer-logo">
                        <i class="fas fa-shield-alt me-2"></i>SchoolConnect
                    </a>
                    <p>
                        Plateforme éducative du Ministère de la Défense pour une gestion optimale des formations et un suivi personnalisé des élèves.
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="footer-links">
                        <h5>Liens</h5>
                        <ul>
                            <li><a href="{{ route('index') }}">Accueil</a></li>
                            <li><a href="#features">Services</a></li>
                            <li><a href="{{ route('register') }}">Inscription</a></li>
                            <li><a href="{{ route('login') }}">Connexion</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-links">
                        <h5>Services</h5>
                        <ul>
                            <li><a href="#">Gestion des cours</a></li>
                            <li><a href="#">Suivi des élèves</a></li>
                            <li><a href="#">Ressources pédagogiques</a></li>
                            <li><a href="#">Formations spécialisées</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="footer-links">
                        <h5>Newsletter</h5>
                        <p>Abonnez-vous pour recevoir nos dernières actualités</p>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Votre email">
                            <button class="btn btn-def" type="button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} SchoolConnect - Ministère de la Défense. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/java.js') }}" ></script>
</body>
</html>
