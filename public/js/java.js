
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des onglets d'authentification
            const authTabs = document.querySelectorAll('.auth-tab');
            const authForms = document.querySelectorAll('.auth-form');

            authTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const formType = this.getAttribute('data-form');

                    // Mettre à jour l'onglet actif
                    authTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Afficher le formulaire correspondant
                    authForms.forEach(form => form.classList.remove('active'));
                    document.getElementById(`${formType}-form`).classList.add('active');
                });
            });

            // Animation au scroll
            const animateElements = () => {
                const elements = document.querySelectorAll('.animate-on-scroll');
                const windowHeight = window.innerHeight;

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const animationPoint = windowHeight - 100;

                    if(elementPosition < animationPoint) {
                        element.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                });
            };

            // Initial check
            animateElements();

            // Check on scroll
            window.addEventListener('scroll', animateElements);

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if(targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if(targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });

