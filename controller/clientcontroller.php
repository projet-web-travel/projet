<?php
require_once '../config/database.php';
require_once '../model/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $user = new User($conn);

    if (isset($_POST["action"]) && $_POST["action"] === "register") {
        // Récupération des données du formulaire d'inscription
        $user->nom = $_POST["nom"] ?? '';
        $user->prenom = $_POST["prenom"] ?? '';
        $user->email = $_POST["email"] ?? '';
        $user->telephone = $_POST["telephone"] ?? '';
        $user->date_naissance = $_POST["date_naissance"] ?? '';
        $user->mot_de_passe = $_POST["password"] ?? '';

        // Enregistrement dans la base
        if ($user->register()) {
            header("Location: ../success.php");
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }

    } elseif (isset($_POST["action"]) && $_POST["action"] === "login") {
        // Connexion
        $email = $_POST["email"] ?? '';
        $mot_de_passe = $_POST["password"] ?? '';

        if ($user->login($email, $mot_de_passe)) {
            // Connexion réussie, on peut démarrer une session
            // session_start();
            // $_SESSION['user'] = $email;

            header("Location: ../success.php");
            exit();
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    }
}
?>
