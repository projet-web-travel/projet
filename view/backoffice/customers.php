<?php
// Affiche toutes les erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$db = 'agence_voyage';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base OK<br>";
    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
      echo "<div style='color: green; font-weight: bold;'>✅ Client supprimé avec succès !</div>";
  }
  
    // Si la requête est POST (pas obligatoire ici, sauf pour un futur traitement)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<pre>";
        var_dump($_POST);
        echo "</pre>";
    }

    // Récupérer la liste des utilisateurs
    $sql = "SELECT Id, nom, prenom, date_naissance, telephone, email, mot_de_passe FROM utilisateurs ORDER BY nom";
    $stmt = $pdo->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Clients - TRIPPED</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="tableau-de-bord.css">
    <style>
        /* Style simplifié (conserve ton style initial) */
        .customer-container { padding: 20px; }
        .customer-table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
            background-color: white; border-radius: 8px; overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .customer-table th, .customer-table td { padding: 12px 15px; text-align: left; }
        .customer-table thead { background-color: #f1f5f9; }
        .customer-table th { font-weight: 600; color: #475569; }
        .customer-table tbody tr:hover { background-color: #f8fafc; }
        .customer-table td { border-top: 1px solid #e2e8f0; }
        .action-btn {
            padding: 6px 12px; border: none; border-radius: 4px;
            cursor: pointer; font-size: 14px; margin-right: 5px;
        }
        .edit-btn { background-color: #3b82f6; color: white; }
        .delete-btn { background-color: #ef4444; color: white; }
        .add-btn {
            background-color: #10b981; color: white;
            padding: 8px 16px; border: none; border-radius: 4px;
            font-weight: 600; cursor: pointer; display: flex;
            align-items: center; margin-bottom: 20px;
        }
        .add-btn i { margin-right: 8px; }
        .header-actions {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px;
        }
        .search-box {
            display: flex; align-items: center;
            background: white; border-radius: 8px;
            padding: 8px 15px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-box input {
            border: none; outline: none;
            padding: 5px; width: 250px;
        }
        .search-box i { color: #64748b; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">TRIPPED</div>
        <a href="index.html"><i class="fas fa-house-user"></i> Dashboard</a>
        <a href="#"><i class="fas fa-calendar-day"></i> Bookings</a>
        <a href="#"><i class="fas fa-bus"></i> Transports</a>
        <a href="customers.php" class="active"><i class="fas fa-users"></i> Customers</a>
        <a href="#"><i class="fas fa-star-half-alt"></i> Reviews</a>
        <a href="tableau-de-bord-tour.php"><i class="fas fa-route"></i> Events</a>
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

        <div class="customer-container">
            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Rechercher un client...">
                </div>
                <button class="add-btn" onclick="location.href='addcustomer.php'">
                <i class="fas fa-plus"></i> Ajouter un client
                </button>

            </div>

            <table class="customer-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Mot de passe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($clients) > 0): ?>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['Id']) ?></td>
                                <td><?= htmlspecialchars($client['nom']) ?></td>
                                <td><?= htmlspecialchars($client['prenom']) ?></td>
                                <td><?= htmlspecialchars($client['date_naissance']) ?></td>
                                <td><?= htmlspecialchars($client['telephone']) ?></td>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars(substr($client['mot_de_passe'], 0, 10)) ?>...</td>
                                <td>
                                <button class="action-btn edit-btn" onclick="location.href='editcustomer.php?id=<?= $client['Id'] ?>'">
                                <i class="fas fa-edit"></i>
                                </button>


                                    <button class="action-btn delete-btn" onclick="confirmDelete(<?= $client['Id'] ?>)">
                                       <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align:center;">Aucun client trouvé</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Recherche dynamique
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.customer-table tbody tr').forEach(row => {
                const nom = row.cells[1].textContent.toLowerCase();
                const prenom = row.cells[2].textContent.toLowerCase();
                const email = row.cells[5].textContent.toLowerCase();
                const tel = row.cells[4].textContent.toLowerCase();
                row.style.display = (nom.includes(searchTerm) || prenom.includes(searchTerm) || email.includes(searchTerm) || tel.includes(searchTerm)) ? '' : 'none';
            });
        });

        // Confirmation de suppression
        function confirmDelete(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
                location.href = 'deletecustomer.php?id=' + id;
            }
        }
    </script>
</body>
</html>

<?php
$pdo = null; // Fermer la connexion proprement
?>
