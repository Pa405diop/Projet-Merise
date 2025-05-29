<?php
require_once '../include/db.php';
require_once '../include/header.php';
require_once '../include/functions.php';

// S'assurer que $pdo est bien défini
if (!isset($pdo) || !$pdo instanceof PDO) {
    die("Erreur de connexion à la base de données.");
}

if (!estSecretaire()) {
    header("Location: /login.php");
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout d'un nouvel appartement
    if (isset($_POST['ajouter'])) {
        $titre = trim($_POST['titre']);
        $description = trim($_POST['description']);
        $adresse = trim($_POST['adresse']);
        $prix = (float)$_POST['prix'];
        $chambres = (int)$_POST['chambres'];
        $disponible = isset($_POST['disponible']) ? 1 : 0;
        
        // Gestion des images
        $images = [];
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($_FILES['photo'.$i]['name'])) {
                $images[] = uploadImage($_FILES['photo'.$i]);
            }
        }
        
        $image_principale = !empty($images) ? $images[0] : 'default.jpg';
        
        // Enregistrement en BDD
        $stmt = $pdo->prepare("INSERT INTO appartements 
                              (titre, description, adresse, prix_par_nuit, nb_chambres, 
                              disponible, image_principale, galerie_images, id_proprietaire)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $titre, $description, $adresse, $prix, $chambres,
            $disponible, $image_principale, json_encode($images), $_SESSION['user_id']
        ]);
        
        $_SESSION['success'] = "Appartement ajouté avec succès!";
        
        
    }
    // Suppression d'un appartement
    elseif (isset($_POST['supprimer'])) {
        $id = (int)$_POST['id'];
        
        // Vérification que l'appartement appartient bien au propriétaire
        $stmt = $pdo->prepare("DELETE FROM appartements 
                              WHERE id = ? AND id_proprietaire = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        
        $_SESSION['success'] = "Appartement supprimé avec succès!";
        header("Location: mes-appartements.php");
        exit;
    }
}

// Récupération des appartements du propriétaire
$appartements = getAppartementsParProprietaire($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Appartements - DakarHaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .photo-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 5px;
            cursor: pointer;
        }
        .photo-preview:hover {
            opacity: 0.8;
        }
        .card-appartement {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .card-appartement:hover {
            transform: translateY(-5px);
        }
        .disponible-true {
            border-left: 5px solid #28a745;
        }
        .disponible-false {
            border-left: 5px solid #dc3545;
        }
    </style>
</head>
<body>
    
    
    <div class="container py-5">
        <!-- Messages de succès/erreur -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-buildings"></i> Mes Appartements</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajoutModal">
                <i class="bi bi-plus-circle"></i> Ajouter un bien
            </button>
        </div>
        
        <!-- Liste des appartements -->
        <div class="row">
            <?php foreach ($appartements as $appart): ?>
                <div class="col-md-6">
                    <div class="card card-appartement <?= 'disponible-' . ($appart['disponible'] ? 'true' : 'false') ?>">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="../assets/images/appartements/<?= $appart['image_principale'] ?>" 
                                     class="img-fluid rounded-start h-100" 
                                     alt="<?= htmlspecialchars($appart['titre']) ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($appart['titre']) ?></h5>
                                    <p class="card-text">
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($appart['adresse']) ?>
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-currency-euro"></i> <?= number_format($appart['prix_par_nuit'], 0, ',', ' ') ?> FCFA/nuit
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-<?= $appart['disponible'] ? 'success' : 'danger' ?>">
                                            <?= $appart['disponible'] ? 'Disponible' : 'Indisponible' ?>
                                        </span>
                                        <div>
                                            <a href="modifier-appartement.php?id=<?= $appart['id'] ?>" 
                                               class="btn btn-sm btn-warning">
                                               <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $appart['id'] ?>">
                                                <button type="submit" name="supprimer" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Supprimer définitivement cet appartement ?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal d'ajout -->
    <div class="modal fade" id="ajoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un nouvel appartement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Titre*</label>
                            <input type="text" name="titre" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description*</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adresse*</label>
                                <input type="text" name="adresse" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Prix/nuit (FCFA)*</label>
                                <input type="number" name="prix" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nombre de chambres*</label>
                                <input type="number" name="chambres" class="form-control" min="1" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="disponible" class="form-check-input" id="disponible" checked>
                            <label class="form-check-label" for="disponible">Disponible immédiatement</label>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Photos (10 max)</label>
                            <div class="row" id="photos-container">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                <div class="col-4 col-md-3 mb-3">
                                    <input type="file" name="photo<?= $i ?>" class="form-control photo-input" 
                                           accept="image/*" onchange="previewImage(this, <?= $i ?>)">
                                    <img src="#" id="preview<?= $i ?>" class="photo-preview d-none mt-2">
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="ajouter" class="btn btn-primary">Publier l'appartement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    <script>
        // Prévisualisation des images
        function previewImage(input, num) {
            const preview = document.getElementById('preview'+num);
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.classList.add('d-none');
            }
        }
    </script>
</body>
</html>
<?php
require_once '../include/footer.php';
?>