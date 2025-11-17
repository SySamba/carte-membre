<?php
require_once 'classes/Membre.php';

$membre_obj = new Membre();
$message = '';
$type_message = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ajouter':
                $photo = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $photo = $membre_obj->uploadPhoto($_FILES['photo']);
                }
                
                $data = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'date_naissance' => $_POST['date_naissance'] ?: null,
                    'telephone' => $_POST['telephone'],
                    'email' => $_POST['email'] ?: null,
                    'adresse' => $_POST['adresse'],
                    'photo' => $photo,
                    'role' => $_POST['role'],
                    'statut' => $_POST['statut'],
                    'date_adhesion' => $_POST['date_adhesion']
                ];
                
                if ($membre_obj->ajouter($data)) {
                    $message = "Membre ajouté avec succès !";
                    $type_message = "success";
                } else {
                    $message = "Erreur lors de l'ajout du membre.";
                    $type_message = "danger";
                }
                break;
                
            case 'modifier':
                $photo = $_POST['photo_actuelle'];
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $new_photo = $membre_obj->uploadPhoto($_FILES['photo']);
                    if ($new_photo) {
                        $photo = $new_photo;
                    }
                }
                
                $data = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'date_naissance' => $_POST['date_naissance'] ?: null,
                    'telephone' => $_POST['telephone'],
                    'email' => $_POST['email'] ?: null,
                    'adresse' => $_POST['adresse'],
                    'photo' => $photo,
                    'role' => $_POST['role'],
                    'statut' => $_POST['statut']
                ];
                
                if ($membre_obj->modifier($_POST['id'], $data)) {
                    $message = "Membre modifié avec succès !";
                    $type_message = "success";
                } else {
                    $message = "Erreur lors de la modification.";
                    $type_message = "danger";
                }
                break;
                
            case 'supprimer':
                if ($membre_obj->supprimer($_POST['id'])) {
                    $message = "Membre supprimé avec succès !";
                    $type_message = "success";
                } else {
                    $message = "Erreur lors de la suppression.";
                    $type_message = "danger";
                }
                break;
        }
    }
}

// Recherche
$terme_recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
if ($terme_recherche) {
    $membres = $membre_obj->rechercher($terme_recherche);
} else {
    $membres = $membre_obj->obtenirTous();
}

$stats = $membre_obj->obtenirStatistiques();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres</title>
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

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 20px auto;
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            color: white;
            padding: 30px;
        }

        .stats-card {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .membre-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #0b843e;
            transition: all 0.3s ease;
        }

        .membre-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .photo-membre-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #0b843e;
            object-fit: cover;
        }

        .btn-khombole {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-khombole:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }

        .search-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin: 20px;
        }

        .modal-header {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <!-- En-tête -->
            <div class="header-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1><i class="fas fa-users me-3"></i>Gestion des Membres</h1>
                        <p class="mb-0">Association de Khombole</p>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <div class="stats-card">
                                    <h3><?php echo $stats['total']; ?></h3>
                                    <small>Total Membres</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-card">
                                    <h3><?php echo $stats['actifs']; ?></h3>
                                    <small>Membres Actifs</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-card">
                                    <h3><?php echo count($stats['roles']); ?></h3>
                                    <small>Rôles Différents</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?php echo $type_message; ?> alert-dismissible fade show m-3" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Section de recherche et actions -->
            <div class="search-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <input type="text" class="form-control me-2" name="recherche" 
                                   placeholder="Rechercher un membre..." 
                                   value="<?php echo htmlspecialchars($terme_recherche); ?>">
                            <button type="submit" class="btn btn-khombole">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-khombole me-2" data-bs-toggle="modal" data-bs-target="#ajouterModal">
                            <i class="fas fa-plus me-2"></i>Ajouter Membre
                        </button>
                        <a href="scanner_qr.php" class="btn btn-outline-success">
                            <i class="fas fa-qrcode me-2"></i>Scanner QR
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liste des membres -->
            <div class="p-3">
                <?php if (empty($membres)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4>Aucun membre trouvé</h4>
                        <p class="text-muted">
                            <?php echo $terme_recherche ? 'Aucun résultat pour votre recherche.' : 'Commencez par ajouter des membres.'; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($membres as $membre): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="membre-card">
                                    <div class="d-flex align-items-center mb-3">
                                        <?php if ($membre['photo'] && file_exists('uploads/photos/' . $membre['photo'])): ?>
                                            <img src="uploads/photos/<?php echo htmlspecialchars($membre['photo']); ?>" 
                                                 alt="Photo" class="photo-membre-small me-3">
                                        <?php else: ?>
                                            <div class="photo-membre-small me-3 d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($membre['role']); ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($membre['telephone']); ?></small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-<?php echo $membre['statut'] === 'Actif' ? 'success' : ($membre['statut'] === 'Inactif' ? 'secondary' : 'warning'); ?>">
                                            <?php echo htmlspecialchars($membre['statut']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="btn-group w-100" role="group">
                                        <a href="generer_carte.php?id=<?php echo $membre['id']; ?>" 
                                           class="btn btn-sm btn-success" title="Voir carte">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                        <button class="btn btn-sm btn-primary" 
                                                onclick="modifierMembre(<?php echo htmlspecialchars(json_encode($membre)); ?>)" 
                                                title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="confirmerSuppression(<?php echo $membre['id']; ?>, '<?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>')" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Membre -->
    <div class="modal fade" id="ajouterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Ajouter un Membre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="ajouter">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" name="nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" name="prenom" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" name="telephone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" name="date_naissance">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date d'adhésion *</label>
                                    <input type="date" class="form-control" name="date_adhesion" 
                                           value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Adresse *</label>
                            <textarea class="form-control" name="adresse" rows="2" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rôle</label>
                                    <select class="form-select" name="role">
                                        <option value="Membre">Membre</option>
                                        <option value="Responsable">Responsable</option>
                                        <option value="Président">Président</option>
                                        <option value="Vice-Président">Vice-Président</option>
                                        <option value="Secrétaire">Secrétaire</option>
                                        <option value="Trésorier">Trésorier</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut">
                                        <option value="Actif">Actif</option>
                                        <option value="Inactif">Inactif</option>
                                        <option value="Suspendu">Suspendu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-khombole">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Membre -->
    <div class="modal fade" id="modifierModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Modifier le Membre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="formModifier">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="modifier">
                        <input type="hidden" name="id" id="modifier_id">
                        <input type="hidden" name="photo_actuelle" id="modifier_photo_actuelle">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" name="nom" id="modifier_nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" name="prenom" id="modifier_prenom" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" name="telephone" id="modifier_telephone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="modifier_email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" name="date_naissance" id="modifier_date_naissance">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rôle</label>
                                    <select class="form-select" name="role" id="modifier_role">
                                        <option value="Membre">Membre</option>
                                        <option value="Responsable">Responsable</option>
                                        <option value="Président">Président</option>
                                        <option value="Vice-Président">Vice-Président</option>
                                        <option value="Secrétaire">Secrétaire</option>
                                        <option value="Trésorier">Trésorier</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Adresse *</label>
                            <textarea class="form-control" name="adresse" rows="2" id="modifier_adresse" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut" id="modifier_statut">
                                        <option value="Actif">Actif</option>
                                        <option value="Inactif">Inactif</option>
                                        <option value="Suspendu">Suspendu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nouvelle photo</label>
                                    <input type="file" class="form-control" name="photo" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-khombole">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation Suppression -->
    <div class="modal fade" id="suppressionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le membre <strong id="nom_suppression"></strong> ?</p>
                    <p class="text-danger"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" id="id_suppression">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function modifierMembre(membre) {
            document.getElementById('modifier_id').value = membre.id;
            document.getElementById('modifier_nom').value = membre.nom;
            document.getElementById('modifier_prenom').value = membre.prenom;
            document.getElementById('modifier_telephone').value = membre.telephone;
            document.getElementById('modifier_email').value = membre.email || '';
            document.getElementById('modifier_date_naissance').value = membre.date_naissance || '';
            document.getElementById('modifier_adresse').value = membre.adresse;
            document.getElementById('modifier_role').value = membre.role;
            document.getElementById('modifier_statut').value = membre.statut;
            document.getElementById('modifier_photo_actuelle').value = membre.photo || '';
            
            new bootstrap.Modal(document.getElementById('modifierModal')).show();
        }

        function confirmerSuppression(id, nom) {
            document.getElementById('id_suppression').value = id;
            document.getElementById('nom_suppression').textContent = nom;
            new bootstrap.Modal(document.getElementById('suppressionModal')).show();
        }
    </script>
</body>
</html>
