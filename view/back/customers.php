<?php
// Désactiver l'affichage des notices
error_reporting(E_ALL & ~E_NOTICE);

// Inclusion des fichiers nécessaires
require'../../config.php';
require'../../controller/UserController.php';
// Création de la connexion à la base de données
$db = new Database();
$pdo = $db->getConnection();

// Création d'une instance de UserController
$userController = new UserController($pdo);

// Variables pour les messages et l'affichage des formulaires
$message = '';
$messageType = ''; // success ou danger
$activeForm = ''; // '', 'add', ou 'edit'
$clientToEdit = null;

// Traitement des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si le bouton Annuler est cliqué, on l'intercepte avant toute validation de formulaire
    if (isset($_POST['cancel_action'])) {
        // Rediriger vers la même page sans paramètres
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // Ajouter un client
            case 'add':
                $data = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'email' => $_POST['email'],
                    'mot_de_passe' => $_POST['mot_de_passe'],
                    'telephone' => $_POST['telephone'],
                    'date_naissance' => $_POST['date_naissance']
                ];
                $message = $userController->register($data);
                $messageType = strpos($message, 'réussie') !== false ? 'success' : 'danger';
                break;
                
            // Modifier un client
            case 'edit':
                $data = [
                    'id' => $_POST['id'],
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'email' => $_POST['email'],
                    'mot_de_passe' => isset($_POST['mot_de_passe']) && !empty($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : null,
                    'telephone' => $_POST['telephone'],
                    'date_naissance' => $_POST['date_naissance']
                ];
                $message = $userController->updateUser($data);
                $messageType = strpos($message, 'réussie') !== false ? 'success' : 'danger';
                break;
                
            // Afficher le formulaire d'ajout
            case 'showAddForm':
                $activeForm = 'add';
                break;
                
            // Afficher le formulaire de modification
            case 'showEditForm':
                $clientToEdit = $userController->getCustomerById($_POST['id']);
                if ($clientToEdit) {
                    $activeForm = 'edit';
                } else {
                    $message = "Client non trouvé.";
                    $messageType = 'danger';
                }
                break;
                
            // Supprimer un client via POST
            case 'delete':
                if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                    $message = $userController->deleteUser($_POST['id']);
                    $messageType = strpos($message, 'réussie') !== false ? 'success' : 'danger';
                } else {
                    // Afficher confirmation
                    $clientToDelete = $userController->getCustomerById($_POST['id']);
                    if ($clientToDelete) {
                        $activeForm = 'delete';
                    } else {
                        $message = "Client non trouvé.";
                        $messageType = 'danger';
                    }
                }
                break;
        }
    }
}

// Traitement des requêtes GET pour la suppression (support maintenu pour compatibilité)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    // Afficher écran de confirmation
    $clientToDelete = $userController->getCustomerById($_GET['id']);
    if ($clientToDelete) {
        $activeForm = 'delete';
    } else {
        $message = "Client non trouvé.";
        $messageType = 'danger';
    }
}

// Récupération des clients (uniquement si on n'est pas en train d'éditer ou d'ajouter)
if ($activeForm === '' || $activeForm === 'delete') {
    $clients = $userController->getAllCustomers()->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients - TRIPPED</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #3580BB;
            color: #fff;
            position: fixed;
            transition: width 0.3s ease;
            z-index: 100;
        }
        .sidebar .logo {
            text-align: center;
            font-size: 40px;
            font-weight: 600;
            color: #fff;
            padding: 50px 0;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            border-radius: 8px;
            margin-left: 20px;
        }
        .sidebar a i {
            margin-right: 15px;
            font-size: 20px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #2f3640;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 290px); /* Ajusté pour éviter les débordements */
            min-height: 100vh;
            background-color: #fff;
            overflow-x: hidden; /* Empêche le défilement horizontal */
        }
        .top-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .content h1 {
            font-size: 38px;
            color: #333;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-form {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 8px;
            padding: 8px 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
        }
        .search-form input {
            border: none;
            outline: none;
            padding: 5px;
            width: 250px;
        }
        .search-form i {
            color: #64748b;
            margin-right: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .add-btn {
            background-color: #10b981;
            color: white;
        }
        .add-btn:hover {
            background-color: #059669;
        }
        .add-btn i, .cancel-btn i {
            margin-right: 8px;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: white;
            margin-left: 10px;
        }
        .cancel-btn:hover {
            background-color: #5a6268;
        }
        .customer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            table-layout: fixed; /* Empêche le tableau de s'élargir */
        }
        .customer-table th,
        .customer-table td {
            padding: 12px 15px;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis; /* Ajoute des points de suspension aux textes longs */
            white-space: nowrap; /* Empêche le retour à la ligne */
        }
        .customer-table thead {
            background-color: #f1f5f9;
        }
        .customer-table th {
            font-weight: 600;
            color: #475569;
        }
        .customer-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .customer-table td {
            border-top: 1px solid #e2e8f0;
        }
        .customer-table th:nth-child(1) { width: 5%; } /* ID */
        .customer-table th:nth-child(2) { width: 15%; } /* Nom */
        .customer-table th:nth-child(3) { width: 15%; } /* Prénom */
        .customer-table th:nth-child(4) { width: 15%; } /* Date naissance */
        .customer-table th:nth-child(5) { width: 15%; } /* Téléphone */
        .customer-table th:nth-child(6) { width: 20%; } /* Email */
        .customer-table th:nth-child(7) { width: 15%; } /* Actions */
        
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 5px;
            transition: all 0.3s ease;
        }
        .edit-btn {
            background-color: #3b82f6;
            color: white;
        }
        .edit-btn:hover {
            background-color: #2563eb;
        }
        .delete-btn {
            background-color: #ef4444;
            color: white;
        }
        .delete-btn:hover {
            background-color: #dc2626;
        }
        /* Styles pour les formulaires en modal */
        .form-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Largeur maximale du formulaire */
            margin: 0 auto;
        }
        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            box-sizing: border-box; /* Important pour éviter les débordements */
        }
        .form-control:focus {
            border-color: #3b82f6;
            outline: none;
        }
        /* Style pour les formulaires en 2 colonnes */
        .form-row {
            display: flex;
            flex-wrap: wrap; /* Pour s'adapter en version mobile */
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-col {
            flex: 1;
            min-width: 200px; /* Largeur minimale pour éviter des colonnes trop étroites */
        }
        .submit-btn {
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #2563eb;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }
        .alert i {
            margin-right: 10px;
            font-size: 18px;
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-right {
            display: flex;
            align-items: center;
        }
        .confirm-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px; /* Plus petit pour la confirmation */
            margin: 0 auto;
        }
        .confirm-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .confirm-text {
            margin-bottom: 20px;
        }
        .confirm-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .pagination a {
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #3b82f6;
            color: white;
            border: 1px solid #3b82f6;
        }
        .pagination a:hover:not(.active) {background-color: #ddd;}
        /* Styles pour les modals plus petits */
        .small-modal {
            max-width: 400px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .sidebar.active {
                width: 250px;
            }
            .content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
            .top-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .user-info {
                margin-top: 10px;
            }
            .header-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-form {
                width: 100%;
                margin-bottom: 10px;
                margin-right: 0;
            }
            .add-btn {
                width: 100%;
                justify-content: center;
            }
            .customer-table {
                font-size: 12px; /* Police plus petite pour mobile */
            }
            .action-btn {
                padding: 4px 8px;
                font-size: 12px;
            }
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            .form-col {
                width: 100%;
            }
            /* Ajuster le tableau pour mobile */
            .customer-table th:nth-child(4),
            .customer-table td:nth-child(4),
            .customer-table th:nth-child(5),
            .customer-table td:nth-child(5) {
                display: none; /* Cacher certaines colonnes en mobile */
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">TRIPPED</div>
        <a href="dashboard.php"><i class="fas fa-house-user"></i> Dashboard</a>
        <a href="#"><i class="fas fa-calendar-day"></i> Bookings</a>
        <a href="#"><i class="fas fa-bus"></i> Transports</a>
        <a href="customers.php" class="active"><i class="fas fa-users"></i> Customers</a>
        <a href="#"><i class="fas fa-star-half-alt"></i> Reviews</a>
        <a href="dashboard-tour.php"><i class="fas fa-route"></i> Events</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="#" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="content">
        <div class="top-content">
            <h1>Gestion des Clients</h1>
            <div class="user-info">
                <i class="fas fa-user"></i>
                <span>John Doe</span>
            </div>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($activeForm === 'add'): ?>
            <!-- Formulaire d'ajout en modal -->
            <div class="form-modal">
                <div class="form-container">
                    <div class="form-title">Ajouter un nouveau client</div>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" id="prenom" name="prenom" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="date_naissance">Date de naissance</label>
                                    <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" id="telephone" name="telephone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="mot_de_passe">Mot de passe</label>
                                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <button type="submit" class="btn submit-btn">Ajouter</button>
                            <button type="submit" name="cancel_action" value="1" class="btn cancel-btn" formnovalidate>
                                <i class="fas fa-times"></i> Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php elseif ($activeForm === 'edit' && $clientToEdit): ?>
            <!-- Formulaire de modification en modal -->
            <div class="form-modal">
                <div class="form-container">
                    <div class="form-title">Modifier le client</div>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($clientToEdit->getId()); ?>">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" class="form-control" value="<?php echo htmlspecialchars($clientToEdit->getNom()); ?>" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" id="prenom" name="prenom" class="form-control" value="<?php echo htmlspecialchars($clientToEdit->getPrenom()); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="date_naissance">Date de naissance</label>
                                    <input type="date" id="date_naissance" name="date_naissance" class="form-control" value="<?php echo htmlspecialchars($clientToEdit->getDateNaissance()); ?>" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" id="telephone" name="telephone" class="form-control" value="<?php echo htmlspecialchars($clientToEdit->getTelephone()); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($clientToEdit->getEmail()); ?>" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="mot_de_passe">Mot de passe (laisser vide pour ne pas modifier)</label>
                                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <button type="submit" class="btn submit-btn">Mettre à jour</button>
                            <button type="submit" name="cancel_action" value="1" class="btn cancel-btn" formnovalidate>
                                <i class="fas fa-times"></i> Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php elseif ($activeForm === 'delete' && isset($clientToDelete)): ?>
            <!-- Confirmation de suppression en petit modal -->
            <div class="form-modal">
                <div class="confirm-container small-modal">
                    <div class="confirm-title">Confirmation de suppression</div>
                    <div class="confirm-text">
                        Êtes-vous sûr de vouloir supprimer le client 
                        <strong><?php echo htmlspecialchars($clientToDelete->getNom() . ' ' . $clientToDelete->getPrenom()); ?></strong> ?
                    </div>
                    <div class="confirm-buttons">
                        <form method="post" action="">
                            <input type="hidden" name="cancel_action" value="1">
                            <button type="submit" class="btn cancel-btn">Annuler</button>
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($clientToDelete->getId()); ?>">
                            <input type="hidden" name="confirm" value="yes">
                            <button type="submit" class="btn delete-btn">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="customer-container">
                <div class="header-actions">
                    <div class="header-right">
                        <form method="get" action="" class="search-form">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Rechercher un client..."
                                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit" style="display:none;">Rechercher</button>
                        </form>
                        <form method="post" action="" style="display: inline;">
                            <button type="submit" name="action" value="showAddForm" class="btn add-btn">
                                <i class="fas fa-plus"></i> Ajouter un client
                            </button>
                        </form>
                    </div>
                </div>
                <div style="overflow-x: auto;"> <!-- Conteneur défilant pour le tableau -->
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date de naissance</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Filtrer les clients si une recherche est effectuée
                            $filteredClients = $clients;
                            if (isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = strtolower($_GET['search']);
                                $filteredClients = array_filter($clients, function($client) use ($search) {
                                    return strpos(strtolower($client['nom']), $search) !== false || 
                                           strpos(strtolower($client['prenom']), $search) !== false || 
                                           strpos(strtolower($client['email']), $search) !== false || 
                                           strpos(strtolower($client['telephone']), $search) !== false;
                                });
                            }
                            
                            if (count($filteredClients) > 0): ?>
                                <?php foreach ($filteredClients as $client): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($client['id']) ?></td>
                                        <td><?= htmlspecialchars($client['nom']) ?></td>
                                        <td><?= htmlspecialchars($client['prenom']) ?></td>
                                        <td><?= htmlspecialchars($client['date_naissance']) ?></td>
                                        <td><?= htmlspecialchars($client['telephone']) ?></td>
                                        <td><?= htmlspecialchars($client['email']) ?></td>
                                        <td>
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="action" value="showEditForm">
                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                <button type="submit" class="action-btn edit-btn" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </form>
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                <button type="submit" class="action-btn delete-btn" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">Aucun client trouvé</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination (optional) -->
                <div class="pagination">
                    <!-- Pagination links would go here if implemented -->
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // JavaScript pour la réactivité mobile pourrait être ajouté ici
    </script>
</body>
</html>