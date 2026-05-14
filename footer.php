    <!-- Footer -->
    <footer id="contact" class="custom-footer pt-5 pb-3">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="col-md-4 mb-4">
                    <h5><?= htmlspecialchars($settings['restaurant_name']) ?></h5>
                    <p>L'excellence de la viande grillée. Dégustez des saveurs uniques dans une ambiance chaleureuse.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liens Rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="footer-link">Accueil</a></li>
                        <li><a href="#menu" class="footer-link">Menu</a></li>
                        <li><a href="#commander" class="footer-link">Commander</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-telephone text-accent"></i> <?= htmlspecialchars($settings['contact_phone']) ?></li>
                        <li><i class="bi bi-envelope text-accent"></i> <?= htmlspecialchars($settings['contact_email']) ?></li>
                        <li><i class="bi bi-geo-alt text-accent"></i> <?= htmlspecialchars($settings['address']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="row border-top border-secondary pt-3 mt-3 text-center">
                <div class="col-12">
                    <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($settings['restaurant_name']) ?>. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Initialisation des animations et scripts persos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation AOS
            AOS.init({
                once: true,
                offset: 50,
                duration: 800,
                easing: 'ease-in-out'
            });

            // Effet navbar au scroll et barre de progression
            window.addEventListener('scroll', function() {
                var navbar = document.querySelector('.custom-navbar');
                var scrollProgress = document.querySelector('.scroll-progress');
                
                // Navbar
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }

                // Calcul de la progression du défilement
                var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var scrolled = (winScroll / height) * 100;
                scrollProgress.style.width = scrolled + "%";
            });
        });
    </script>
</body>
</html>
