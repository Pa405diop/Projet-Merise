<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <!-- Colonne Logo + Description -->
            <div class="col-lg-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-house-door-fill text-info fs-3 me-2"></i>
                    <span class="fs-4 fw-bold">DakarHaven</span>
                </div>
                <p class="text">
                    Plateforme de réservation d'appartements en ligne. 
                    Trouvez le logement parfait pour votre séjour en quelques clics.
                </p>
                <div class="social-icons">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin fs-4"></i></a>
                </div>
            </div>

            <!-- Colonne Liens rapides -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-info mb-4">Liens rapides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="index.php" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right text-info me-1"></i> Accueil
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="appartements.php" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right text-info me-1"></i> Appartements
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="mb-2">
                            <a href="mes-reservations.php" class="text-white text-decoration-none">
                                <i class="bi bi-chevron-right text-info me-1"></i> Mes réservations
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="mb-2">
                        <a href="contact.php" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right text-info me-1"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne Contact -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-info mb-4">Contactez-nous</h5>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <i class="bi bi-geo-alt-fill text-info me-3 mt-1"></i>
                        <span class="text">123 Avenue des Résidences, Dakar</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="bi bi-telephone-fill text-info me-3 mt-1"></i>
                        <span class="text">+221 33 123 45 67</span>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-envelope-fill text-info me-3 mt-1"></i>
                        <span class="text">dakarhaven@gmail.sn</span>
                    </li>
                </ul>
            </div>

            <!-- Colonne Newsletter -->
            <div class="col-lg-3 mb-4">
                <h5 class="text-info mb-4">Newsletter</h5>
                <p class="text mb-3">
                    Abonnez-vous pour recevoir nos offres spéciales.
                </p>
                <form class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre email">
                        <button class="btn btn-info" type="button">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
                <small class="text">
                    <i class="bi bi-lock-fill me-1"></i> Vos données sont sécurisées
                </small>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <!-- Copyright + Mentions -->
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="text mb-0">
                    &copy; <?= date('Y') ?> DakarHaven. Tous droits réservés.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="mentions-legales.php" class="text text-decoration-none me-3">Mentions légales</a>
                <a href="politique-confidentialite.php" class="text text-decoration-none">Politique de confidentialité</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>