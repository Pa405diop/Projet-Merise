<?php
require_once '../include/db.php';
require_once '../include/functions.php';
require_once '../include/header.php'; // Inclut le header si vous en avez un

function estConnecte() {
    return isset($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Réservation Appartements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --beige: #f5f5dc;
            --gris-fonce: #2e2e2e;
            --bleu-info: #0dcaf0;
        }
        body {
            background-color: var(--beige);
        }
        .hero-section {
            background-color: var(--gris-fonce);
            color: white;
            border-radius: 15px;
            padding: 3rem;
            margin-top: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-info-custom {
            background-color: var(--bleu-info);
            border-color: var(--bleu-info);
            color: var(--gris-fonce);
            font-weight: 500;
        }
        .btn-outline-info-custom {
            border-color: var(--bleu-info);
            color: var(--bleu-info);
        }
        .btn-outline-info-custom:hover {
            background-color: var(--bleu-info);
            color: var(--gris-fonce);
        }
        .lead-text {
            color: rgba(255,255,255,0.8);
        }
    </style>
</head>
<body>
    <main class="container my-5">
        <!-- Hero Section -->
        <section class="row align-items-center hero-section">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-info mb-4">Bienvenue sur la Plateforme Réservation</h1>
                <p class="lead lead-text">
                    Trouvez l'appartement idéal pour votre séjour. 
                    Parcourez notre sélection d'appartements de qualité dans les meilleurs quartiers.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <?php if (!estConnecte()): ?>
                        <a href="login.php" class="btn btn-info-custom btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </a>
                        <a href="register.php" class="btn btn-outline-info-custom btn-lg px-4">
                            <i class="bi bi-person-plus"></i> S'inscrire
                        </a>
                    <?php else: ?>
                        <a href="appartements.php" class="btn btn-info-custom btn-lg px-4">
                            <i class="bi bi-search"></i> Voir les appartements
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <img src="building.jpg" alt="Appartement moderne" class="img-fluid rounded-3">
            </div>
        </section>

        <!-- Features Section -->
        <section class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-info fs-1 mb-3"></i>
                        <h3 class="h5">Qualité Garantie</h3>
                        <p class="text-muted">Tous nos appartements sont vérifiés et certifiés par notre équipe.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-credit-card-fill text-info fs-1 mb-3"></i>
                        <h3 class="h5">Paiement Sécurisé</h3>
                        <p class="text-muted">Transactions 100% sécurisées avec cryptage SSL.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-headset text-info fs-1 mb-3"></i>
                        <h3 class="h5">Support 24/7</h3>
                        <p class="text-muted">Notre équipe est disponible pour vous aider à tout moment.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>


</body>
</html>
<?php
include '../include/footer.php'; // Inclut le footer si vous en avez un
?>