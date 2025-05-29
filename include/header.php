<?php
// header.php
session_start();
$est_connecte = isset($_SESSION['user_id']);
$est_admin = $est_connecte && $_SESSION['user_role'] === 'admin';
$est_secretaire = $est_connecte && $_SESSION['user_role'] === 'secretaire';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Réservation Appartements' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --beige: #f5f5dc;
            --gris-fonce: #2e2e2e;
            --bleu-info: #0dcaf0;
        }
        .navbar-custom {
            background-color: var(--gris-fonce);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: white !important;
            position: relative;
            margin: 0 10px;
            font-weight: 500;
        }
        .nav-link:hover {
            color: var(--bleu-info) !important;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--bleu-info);
        }
        .btn-header {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .admin-badge {
            background-color: #dc3545;
            font-size: 0.7rem;
            vertical-align: super;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../public/index.php">
                <i class="bi bi-house-door-fill text-info me-2"></i>
                <span class="text-white fw-bold">DakarHaven</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="bi bi-list text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>" 
                           href="../public/index.php">
                           <i class="bi bi-house-door me-1"></i> Accueil
                        </a>
                    </li>
                    
                    <?php if ($est_connecte): ?>
                        <li class="nav-item">
                            <a class="nav-link "
                               href="../public/appartements.php">
                               <i class="bi bi-search me-1"></i> Appartements
                            </a>
                        </li>
                        
                        <?php if ($est_admin): ?>
                            <!-- Menu déroulant Admin -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="admin/statistiques.php">
                                            <i class="bi bi-graph-up me-2"></i> Statistiques
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="admin/rapports.php">
                                            <i class="bi bi-file-earmark-bar-graph me-2"></i> Rapports
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="admin/audit.php">
                                            <i class="bi bi-shield-lock me-2"></i> Audit
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php elseif ($est_secretaire): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'mes-appartements.php' ? 'active' : '' ?>" 
                                   href="../secretaire/mes_appartements.php">
                                   <i class="bi bi-building me-1"></i> Mes biens
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'mes-reservations.php' ? 'active' : '' ?>" 
                                   href="client/mes-reservations.php">
                                   <i class="bi bi-calendar-check me-1"></i> Mes réservations
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex">
                    <?php if ($est_connecte): ?>
                        <?php if ($est_admin): ?>
                            <!-- Menu Admin -->
                            <div class="dropdown">
                                <a href="#" class="btn btn-danger btn-header dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-shield-lock me-1"></i> Administration
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="admin/utilisateurs.php">
                                            <i class="bi bi-people-fill me-2"></i> Gestion utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="admin/appartements.php">
                                            <i class="bi bi-buildings me-2"></i> Tous les appartements
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="admin/reservations.php">
                                            <i class="bi bi-journal-check me-2"></i> Réservations
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="logout.php">
                                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <!-- Menu Standard (Client/Secretaire) -->
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" 
                                   data-bs-toggle="dropdown">
                                   <i class="bi bi-person-circle fs-4 me-2"></i>
                                   <?= htmlspecialchars($_SESSION['user_nom'] ?? 'Mon compte') ?>
                                   <?php if ($est_secretaire): ?>
                                       <span class="badge bg-info ms-2">Propriétaire</span>
                                   <?php endif; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= $est_secretaire ? 'secretaire/profil.php' : 'client/profil.php' ?>">
                                            <i class="bi bi-person me-2"></i> Profil
                                        </a>
                                    </li>
                                    <?php if ($est_secretaire): ?>
                                    <li>
                                        <a class="dropdown-item" href="secretaire/notifications.php">
                                            <i class="bi bi-bell me-2"></i> Notifications
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="logout.php">
                                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Non connecté -->
                        <a href="login.php" class="btn btn-info btn-header me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                        </a>
                        <a href="register.php" class="btn btn-outline-info btn-header">
                            <i class="bi bi-person-plus me-1"></i> Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>