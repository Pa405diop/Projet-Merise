<?php
require_once '../include/functions.php';
require_once '../include/header.php';

// Vérification admin
if (!estAdmin()) {
    header('Location: /index.php');
    exit;
}

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = cleanInput($_POST['titre']);
    $description = cleanInput($_POST['description']);
    $adresse = cleanInput($_POST['adresse']);
    $prix = (float)$_POST['prix'];
    $chambres = (int)$_POST['chambres'];
    
    // Gestion de l'upload d'image
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = uploadImage($_FILES['image']);
    }

    ajouterAppartement($titre, $description, $adresse, $prix, $chambres, $image);
}

// Récupération de tous les appartements
$appartements = getAllAppartements();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion Appartements</title>
    <!-- Styles et scripts Bootstrap -->
</head>
<body>
    
    
    <div class="container mt-5">
        <h2 class="mb-4"><i class="bi bi-building"></i> Gestion des Appartements</h2>
        
        <!-- Formulaire d'ajout -->
        <div class="card mb-5">
            <div class="card-header bg-dark text-white">
                <h4>Ajouter un nouvel appartement</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Titre*</label>
                                <input type="text" name="titre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse*</label>
                                <input type="text" name="adresse" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix/nuit (FCFA)*</label>
                                <input type="number" name="prix" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre de chambres*</label>
                                <input type="number" name="chambres" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image principale*</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description*</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>

        