<?php
require_once 'db.php';

/******************************
 * VÉRIFICATIONS DE RÔLES
 ******************************/
function estAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function estSecretaire() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'secretaire';
}

function estClient() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client';
}

function estProprietaireAppartement($id_appartement) {
    global $pdo;
    if (!estSecretaire()) return false;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appartements WHERE id = ? AND id_proprietaire = ?");
    $stmt->execute([$id_appartement, $_SESSION['user_id']]);
    return $stmt->fetchColumn() > 0;
}

/******************************
 * GESTION UTILISATEURS
 ******************************/
function ajouterUtilisateur($nom, $email, $mot_de_passe, $telephone, $role = 'client') {
    global $pdo;
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, telephone, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $email, $hashed_password, $telephone, $role]);
    } catch (PDOException $e) {
        error_log("Erreur ajout utilisateur: " . $e->getMessage());
        return false;
    }
}

function getUtilisateurParEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function modifierUtilisateur($id, $nom, $email, $telephone) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, telephone = ? WHERE id = ?");
    return $stmt->execute([$nom, $email, $telephone, $id]);
}

function supprimerUtilisateur($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    return $stmt->execute([$id]);
}

/******************************
 * GESTION APPARTEMENTS
 ******************************/
function ajouterAppartement($titre, $description, $adresse, $prix, $chambres, $image, $id_proprietaire = null) {
    global $pdo;
    $id_proprietaire = $id_proprietaire ?? $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO appartements 
                          (titre, description, adresse, prix_par_nuit, nb_chambres, image, id_proprietaire) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$titre, $description, $adresse, $prix, $chambres, $image, $id_proprietaire]);
}

function getAppartementsDisponibles() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM appartements WHERE disponible = TRUE");
    return $stmt->fetchAll();
}

function getAppartementParId($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM appartements WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAppartementsParProprietaire($id_proprietaire) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM appartements WHERE id_proprietaire = ? ORDER BY id DESC");
    $stmt->execute([$id_proprietaire]);
    return $stmt->fetchAll();
}

function modifierAppartement($id, $titre, $description, $adresse, $prix, $chambres, $disponible) {
    global $pdo;
    if (estSecretaire() && !estProprietaireAppartement($id)) return false;
    
    $stmt = $pdo->prepare("UPDATE appartements SET 
                          titre = ?, description = ?, adresse = ?, 
                          prix_par_nuit = ?, nb_chambres = ?, disponible = ?
                          WHERE id = ?");
    return $stmt->execute([$titre, $description, $adresse, $prix, $chambres, $disponible, $id]);
}

/******************************
 * GESTION RÉSERVATIONS
 ******************************/
function creerReservation($id_utilisateur, $id_appartement, $date_debut, $date_fin) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO reservations 
                          (id_utilisateur, id_appartement, date_debut, date_fin) 
                          VALUES (?, ?, ?, ?)");
    return $stmt->execute([$id_utilisateur, $id_appartement, $date_debut, $date_fin]);
}

function getReservationsUtilisateur($id_utilisateur) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT r.*, a.titre, a.adresse 
                          FROM reservations r
                          JOIN appartements a ON r.id_appartement = a.id
                          WHERE r.id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);
    return $stmt->fetchAll();
}

function getReservationsParProprietaire($id_proprietaire) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT r.*, a.titre, u.nom AS client_nom
                          FROM reservations r
                          JOIN appartements a ON r.id_appartement = a.id
                          JOIN utilisateurs u ON r.id_utilisateur = u.id
                          WHERE a.id_proprietaire = ?");
    $stmt->execute([$id_proprietaire]);
    return $stmt->fetchAll();
}

function annulerReservation($id_reservation) {
    global $pdo;
    if (estSecretaire() && !estProprietaireReservation($id_reservation)) return false;
    
    $stmt = $pdo->prepare("UPDATE reservations SET statut = 'annulée' WHERE id = ?");
    return $stmt->execute([$id_reservation]);
}

function confirmerReservation($id_reservation) {
    global $pdo;
    if (estSecretaire() && !estProprietaireReservation($id_reservation)) return false;
    
    $stmt = $pdo->prepare("UPDATE reservations SET statut = 'confirmée' WHERE id = ?");
    return $stmt->execute([$id_reservation]);
}

/******************************
 * FONCTIONS UTILITAIRES
 ******************************/
function verifierDisponibilite($id_appartement, $date_debut, $date_fin) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations 
                          WHERE id_appartement = ? 
                          AND statut = 'confirmée'
                          AND ((date_debut BETWEEN ? AND ?) 
                          OR (date_fin BETWEEN ? AND ?))");
    $stmt->execute([$id_appartement, $date_debut, $date_fin, $date_debut, $date_fin]);
    return $stmt->fetchColumn() == 0;
}

function uploadImage($file) {
    $targetDir = rtrim(__DIR__.'/../images', '/\\') . DIRECTORY_SEPARATOR;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid().'.'.$ext;
    $targetFile = $targetDir.$filename;

    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($ext), $allowedTypes)) {
        throw new Exception('Format d\'image non supporté');
    }

    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        throw new Exception('Erreur lors de l\'upload');
    }
    return $filename;
}

function estProprietaireReservation($id_reservation) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations r
                          JOIN appartements a ON r.id_appartement = a.id
                          WHERE r.id = ? AND a.id_proprietaire = ?");
    $stmt->execute([$id_reservation, $_SESSION['user_id']]);
    return $stmt->fetchColumn() > 0;
}
function getAppartementsFiltres($filtres) {
    global $pdo;
    
    $sql = "SELECT * FROM appartements WHERE disponible = TRUE";
    $params = [];
    
    // Filtre par ville
    if (!empty($filtres['ville'])) {
        $sql .= " AND ville = ?";
        $params[] = $filtres['ville'];
    }
    
    // Filtre par nombre de chambres
    if (!empty($filtres['chambres'])) {
        $sql .= " AND nb_chambres >= ?";
        $params[] = $filtres['chambres'];
    }
    
    // Filtre par prix
    if (!empty($filtres['prix_min'])) {
        $sql .= " AND prix_par_nuit >= ?";
        $params[] = $filtres['prix_min'];
    }
    
    if (!empty($filtres['prix_max'])) {
        $sql .= " AND prix_par_nuit <= ?";
        $params[] = $filtres['prix_max'];
    }
    
    $sql .= " ORDER BY prix_par_nuit ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $appartements = $stmt->fetchAll();
    
    // Filtre supplémentaire par disponibilité aux dates
    if (!empty($filtres['date_debut']) && !empty($filtres['date_fin'])) {
        return array_filter($appartements, function($appart) use ($filtres) {
            return verifierDisponibilite($appart['id'], $filtres['date_debut'], $filtres['date_fin']);
        });
    }
    
    return $appartements;
}

function getVillesDisponibles() {
    global $pdo;
    $stmt = $pdo->query("SELECT DISTINCT ville FROM appartements WHERE disponible = TRUE ORDER BY ville");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}