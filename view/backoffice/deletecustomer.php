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

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        // Requête de suppression
        $sql = "DELETE FROM utilisateurs WHERE Id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Rediriger avec le paramètre 'deleted' dans l'URL
            header('Location: customers.php?deleted=1');
            exit;
        } else {
            echo "❌ Erreur de suppression du client.";
        }
    } else {
        echo "❌ L'ID du client n'est pas valide.";
    }
} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
    die();
}

// Fermer la connexion proprement
$pdo = null;
?>
