<?php
require_once '../include/functions.php';

$erreurs = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage des données
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmation_mdp = $_POST['confirmation_mdp'];
    $role = $_POST['role'] === 'proprietaire' ? 'secretaire' : 'client'; // Conversion du rôle

    // Validation
    if (empty($nom) || empty($email) || empty($telephone) || empty($mot_de_passe)) {
        $erreurs[] = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Format d'email invalide.";
    } elseif ($mot_de_passe !== $confirmation_mdp) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($mot_de_passe) < 8) {
        $erreurs[] = "Le mot de passe doit faire au moins 8 caractères.";
    } else {
        // Enregistrement
        if (ajouterUtilisateur($nom, $email, $mot_de_passe, $telephone, $role)) {
            $success = true;
        } else {
            $erreurs[] = "Cet email est déjà utilisé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - DakarHaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5dc;
        }
        .card-inscription {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .role-btn {
            transition: all 0.3s;
        }
        .role-btn.active {
            background-color: #2e2e2e;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-inscription">
                    <div class="card-header bg-dark text-white">
                        <h3 class="mb-0"><i class="bi bi-person-plus"></i> Créer un compte</h3>
                    </div>
                    <div class="card-body p-4">

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Inscription réussie ! <a href="login.php">Connectez-vous</a>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($erreurs as $erreur): ?>
                            <div class="alert alert-danger"><?= $erreur ?></div>
                        <?php endforeach; ?>

                        <form method="POST" novalidate>
                            <!-- Choix du rôle -->
                            <div class="mb-4 text-center">
                                <label class="d-block mb-3 fw-bold">Je suis :</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="role" id="role-client" value="client" checked>
                                    <label class="btn btn-outline-dark role-btn" for="role-client">
                                        <i class="bi bi-person"></i> Un client
                                    </label>

                                    <input type="radio" class="btn-check" name="role" id="role-proprio" value="proprietaire">
                                    <label class="btn btn-outline-dark role-btn" for="role-proprio">
                                        <i class="bi bi-house-gear"></i> Un propriétaire
                                    </label>
                                </div>
                            </div>

                            <!-- Informations de base -->
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="nom" name="nom" required
                                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required
                                       value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="mot_de_passe" class="form-label">Mot de passe (8 caractères min)</label>
                                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required minlength="8">
                            </div>

                            <div class="mb-4">
                                <label for="confirmation_mdp" class="form-label">Confirmation du mot de passe</label>
                                <input type="password" class="form-control" id="confirmation_mdp" name="confirmation_mdp" required minlength="8">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-lg">
                                    <i class="bi bi-check-circle"></i> S'inscrire
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des boutons de rôle
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>