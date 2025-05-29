-- Création de la base
CREATE DATABASE IF NOT EXISTS reservation_appartements;
USE reservation_appartements;

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs  (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    telephone VARCHAR(20),
    role ENUM('admin', 'secretaire','client') DEFAULT 'client'
);

-- Table appartements
CREATE TABLE IF NOT EXISTS appartements (

    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proprietaire INT NOT NULL,
    titre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    ville VARCHAR(50) NOT NULL DEFAULT 'Dakar',
    prix_par_nuit DECIMAL(10,2) NOT NULL,
    nb_chambres INT NOT NULL,
    surface INT,
    nb_personnes_max INT DEFAULT 2,
    image_principale VARCHAR(255),
    galerie_images VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_proprietaire) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table réservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_appartement INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_attente', 'confirmée', 'annulée', 'refusée') DEFAULT 'en_attente',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_appartement) REFERENCES appartements(id) ON DELETE CASCADE,
    CONSTRAINT chk_dates CHECK (date_fin > date_debut)
);



