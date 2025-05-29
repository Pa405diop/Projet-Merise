<?php
session_start();
require_once '../include/db.php';
require_once '../include/functions.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Récupération de l'utilisateur
    $utilisateur = getUtilisateurParEmail($email);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $utilisateur['id'];
        $_SESSION['user_email'] = $utilisateur['email'];
        $_SESSION['user_role'] = $utilisateur['role'];
        $_SESSION['user_nom'] = $utilisateur['nom'];

        // Redirection 
        header('Location: index.php');
        
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - RESAAPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5dc;
        }
        .login-card {
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .role-badge {
            position: absolute;
            right: 20px;
            top: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card login-card">
                    <div class="card-header bg-dark text-white position-relative">
                        <h3 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Connexion</h3>
                        
                    </div>
                    <div class="card-body p-4">

                        <?php if ($erreur): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($erreur) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                </div>
                                <a href="motdepasse-oublie.php" class="text-decoration-none">Mot de passe oublié ?</a>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-dark btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                                </button>
                            </div>

                            <div class="text-center">
                                <p>Pas encore de compte ? <a href="register.php" class="text-decoration-none">S'inscrire</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>