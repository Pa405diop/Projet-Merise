<?php
require_once __DIR__.'/../include/functions.php';

// Traitement des filtres
$filtres = [
    'ville' => $_GET['ville'] ?? null,
    'prix_min' => $_GET['prix_min'] ?? null,
    'prix_max' => $_GET['prix_max'] ?? null,
    'chambres' => $_GET['chambres'] ?? null,
    'date_debut' => $_GET['date_debut'] ?? null,
    'date_fin' => $_GET['date_fin'] ?? null
];

// Récupération des appartements filtrés
$appartements = getAppartementsFiltres($filtres);

// Récupération des villes distinctes pour le filtre
$villes = getVillesDisponibles();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appartements disponibles - DakarHaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-appartement {
            transition: transform 0.3s;
            margin-bottom: 20px;
            height: 100%;
        }
        .card-appartement:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .img-appartement {
            height: 200px;
            object-fit: cover;
        }
        .badge-dispo {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .price-tag {
            font-weight: bold;
            color: #2e2e2e;
        }
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php include '../include/header.php'; ?>
    
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-search"></i> Trouvez votre logement idéal</h1>
        
        <!-- Section Filtres -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="ville" class="form-label">Ville</label>
                    <select id="ville" name="ville" class="form-select">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville) ?>" <?= $filtres['ville'] === $ville ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="chambres" class="form-label">Chambres</label>
                    <select id="chambres" name="chambres" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" <?= $filtres['chambres'] == 1 ? 'selected' : '' ?>>1 chambre</option>
                        <option value="2" <?= $filtres['chambres'] == 2 ? 'selected' : '' ?>>2 chambres</option>
                        <option value="3" <?= $filtres['chambres'] == 3 ? 'selected' : '' ?>>3+ chambres</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="prix_min" class="form-label">Prix min (FCFA)</label>
                    <input type="number" id="prix_min" name="prix_min" class="form-control" 
                           placeholder="Min" value="<?= htmlspecialchars($filtres['prix_min'] ?? '') ?>">
                </div>
                
                <div class="col-md-3">
                    <label for="prix_max" class="form-label">Prix max (FCFA)</label>
                    <input type="number" id="prix_max" name="prix_max" class="form-control" 
                           placeholder="Max" value="<?= htmlspecialchars($filtres['prix_max'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="date_debut" class="form-label">Dates de séjour</label>
                    <div class="input-group">
                        <input type="date" id="date_debut" name="date_debut" class="form-control" 
                               value="<?= htmlspecialchars($filtres['date_debut'] ?? '') ?>">
                        <span class="input-group-text">à</span>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" 
                               value="<?= htmlspecialchars($filtres['date_fin'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark me-2">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    <a href="appartements.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Liste des appartements -->
        <div class="row">
            <?php if (empty($appartements)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucun appartement ne correspond à vos critères. Essayez d'élargir votre recherche.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($appartements as $appart): ?>
                    <div class="col-md-4">
                        <div class="card card-appartement">
                            <div class="position-relative">
                                <img src="../assets/images/appartements/<?= htmlspecialchars($appart['image_principale']) ?>" 
                                     class="card-img-top img-appartement" 
                                     alt="<?= htmlspecialchars($appart['titre']) ?>">
                                <span class="badge bg-<?= $appart['disponible'] ? 'success' : 'danger' ?> badge-dispo">
                                    <?= $appart['disponible'] ? 'Disponible' : 'Indisponible' ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($appart['titre']) ?></h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($appart['adresse']) ?>
                                </p>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item">
                                        <i class="bi bi-door-open"></i> <?= $appart['nb_chambres'] ?> chambre(s)
                                    </li>
                                    <li class="list-group-item">
                                        <i class="bi bi-people"></i> Jusqu'à <?= $appart['nb_personnes_max'] ?? 2 ?> personnes
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price-tag">
                                        <?= number_format($appart['prix_par_nuit'], 0, ',', ' ') ?> FCFA/nuit
                                    </span>
                                    <a href="details-appartement.php?id=<?= $appart['id'] ?>" 
                                       class="btn btn-sm btn-dark">
                                       Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../include/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Définir la date minimale pour les champs de date (aujourd'hui)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_debut').min = today;
        
        // Mettre à jour la date minimale de fin quand la date de début change
        document.getElementById('date_debut').addEventListener('change', function() {
            document.getElementById('date_fin').min = this.value;
        });
    </script>
</body>
</html>