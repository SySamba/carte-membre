<?php
require_once 'classes/Membre.php';

$membre_obj = new Membre();
$stats = $membre_obj->obtenirStatistiques();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Association de Khombole - Gestion des Membres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0b843e 0%, #f4e93d 100%);
            min-height: 100vh;
        }

        .hero-section {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 40px auto;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .hero-header {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
            position: relative;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(244,233,61,0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .logo-hero {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-top: 4px solid #0b843e;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
        }

        .btn-khombole {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-khombole:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .btn-secondary-khombole {
            background: linear-gradient(135deg, #f4e93d 0%, #e6d635 100%);
            border: none;
            color: #0b843e;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-secondary-khombole:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            color: #0b843e;
        }

        .stats-section {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 15px;
            margin: 30px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 700;
            color: #0b843e;
            display: block;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }

        .footer-section {
            background: linear-gradient(135deg, #1a5f2e 0%, #0b843e 100%);
            color: white;
            padding: 40px;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <!-- En-tête Hero -->
            <div class="hero-header">
                <img src="logo.png" alt="Logo Association" class="logo-hero">
                <h1 class="display-4 fw-bold mb-3">Association de Khombole</h1>
                <p class="lead mb-4">Système de Gestion des Membres avec Cartes QR</p>
                <p class="mb-0">Gérez efficacement vos membres avec des cartes professionnelles et des QR codes</p>
            </div>

            <!-- Statistiques -->
            <div class="stats-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $stats['total']; ?></span>
                            <div class="stat-label">Membres Total</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $stats['actifs']; ?></span>
                            <div class="stat-label">Membres Actifs</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($stats['roles']); ?></span>
                            <div class="stat-label">Rôles Différents</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités -->
            <div class="container py-5">
                <h2 class="text-center mb-5 fw-bold" style="color: #0b843e;">Fonctionnalités Principales</h2>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="mb-3" style="color: #0b843e;">Gestion des Membres</h4>
                            <p class="text-muted mb-4">Ajoutez, modifiez et gérez tous vos membres avec leurs informations complètes.</p>
                            <a href="gestion_membres.php" class="btn-khombole">
                                <i class="fas fa-arrow-right me-2"></i>Gérer
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h4 class="mb-3" style="color: #0b843e;">Cartes Professionnelles</h4>
                            <p class="text-muted mb-4">Générez des cartes de membres professionnelles avec QR codes intégrés.</p>
                            <a href="gestion_membres.php" class="btn-secondary-khombole">
                                <i class="fas fa-id-card me-2"></i>Créer Cartes
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <h4 class="mb-3" style="color: #0b843e;">Scanner QR Codes</h4>
                            <p class="text-muted mb-4">Scannez les QR codes pour afficher instantanément les informations des membres.</p>
                            <a href="scanner_qr.php" class="btn-khombole">
                                <i class="fas fa-camera me-2"></i>Scanner
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="container pb-5">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <h3 class="mb-4" style="color: #0b843e;">Actions Rapides</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="gestion_membres.php" class="btn-khombole">
                                <i class="fas fa-plus me-2"></i>Ajouter un Membre
                            </a>
                            <a href="scanner_qr.php" class="btn-secondary-khombole">
                                <i class="fas fa-qrcode me-2"></i>Scanner QR Code
                            </a>
                            <a href="gestion_membres.php" class="btn-khombole">
                                <i class="fas fa-search me-2"></i>Rechercher Membre
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer-section">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start text-center">
                    <h5><i class="fas fa-heart me-2"></i>Association de Khombole</h5>
                    <p class="mb-0">Système de gestion des membres - Version 1.0</p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <p class="mb-0">
                        <i class="fas fa-code me-2"></i>
                        Développé avec PHP, MySQL & Bootstrap
                    </p>
                    <small class="opacity-75">© 2024 - Tous droits réservés</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
