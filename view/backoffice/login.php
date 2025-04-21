<?php
// Affiche toutes les erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$db = 'agence_voyage'; // Nom de ta base
$user = 'root';
$pass = '';
$message = '';

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifie que la requête est POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['pass'] ?? '';

        // Requête pour trouver l'utilisateur par email
        $stmt = $pdo->prepare("SELECT id, mot_de_passe FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe SANS hash
        if ($user && $password == $user['mot_de_passe']) {
            // Redirection si le mot de passe est correct
            echo "✅ connexion réussie !";
            exit();
        } else {
            // Identifiants incorrects
            $message = "❌ Identifiants incorrects.";
        }
    }

} catch (PDOException $e) {
    // Gestion des erreurs PDO
    $message = "Erreur : " . $e->getMessage();
}

// Affichage des messages après la logique
if ($message) {
    echo "<p style='color: red;'>$message</p>";
}
?>