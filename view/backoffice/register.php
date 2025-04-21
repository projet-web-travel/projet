<?php
// Affiche toutes les erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$db = 'agence_voyage'; // Remplace si besoin
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion à la base OK<br>";

    // Vérifie si c'est une requête POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      echo "<pre>";
      var_dump($_POST);
      echo "</pre>";

        // Récupérer les données du formulaire
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? '';
        $mot_de_passe = $_POST['pass'] ?? '';
        $confirmation = $_POST['confirm'] ?? '';

        


        // Vérification basique
        if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($date_naissance) || empty($mot_de_passe)) {
            echo "❌ Veuillez remplir tous les champs.";
            exit;
        }

        // Vérification format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "❌ Email invalide.";
            exit;
        }

        // Insertion dans la base de données
        try {
          $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, date_naissance,mot_de_passe) VALUES (?, ?, ?, ?, ?,?)");
          $stmt->execute([$nom, $prenom, $email, $telephone, $date_naissance, $mot_de_passe]);
          echo "✅ Inscription réussie !";
      } catch (PDOException $e) {
          echo "❌ Erreur SQL : " . $e->getMessage();
      }
      

        echo "✅ Inscription réussie !";
    } else {
        echo "⚠️ Pas une requête POST.";
    }
} catch (PDOException $e) {
    echo "❌ Erreur lors de l'inscription : " . $e->getMessage();
}
?>
