<?php
// Affiche les erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion de la configuration PDO
require_once  '../config.php';

// Initialisation de la réponse JSON
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

try {
    // Création d'une instance de la classe Database et récupération de la connexion
    $db = new Database();
    $pdo = $db->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telephone = trim($_POST['telephone'] ?? '');
        $date_naissance = trim($_POST['date_naissance'] ?? '');
        $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
        $confirmation = trim($_POST['confirm_password'] ?? '');

        // Validation des données
        if (empty($nom)) {
            $response['errors']['nom'] = "Le nom est requis.";
        }
        if (empty($prenom)) {
            $response['errors']['prenom'] = "Le prénom est requis.";
        }
        if (empty($email)) {
            $response['errors']['email'] = "L'email est requis.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['errors']['email'] = "Format d'email invalide.";
        }
        if (empty($telephone)) {
            $response['errors']['telephone'] = "Le téléphone est requis.";
        } elseif (!preg_match('/[0-9]$/', $telephone)) {
            $response['errors']['telephone'] = "Numéro de téléphone invalide.";
        }
        if (empty($date_naissance)) {
            $response['errors']['date_naissance'] = "La date de naissance est requise.";
        } elseif (!DateTime::createFromFormat('Y-m-d', $date_naissance)) {
            $response['errors']['date_naissance'] = "Format de date invalide (YYYY-MM-DD).";
        }
        if (empty($mot_de_passe)) {
            $response['errors']['mot_de_passe'] = "Le mot de passe est requis.";
        } elseif (strlen($mot_de_passe) < 8) {
            $response['errors']['mot_de_passe'] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($mot_de_passe !== $confirmation) {
            $response['errors']['confirm_password'] = "Les mots de passe ne correspondent pas.";
        }

        // Vérifier si l'email existe déjà
        if (empty($response['errors'])) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $response['errors']['email'] = "L'email existe déjà.";
            }
        }

        // Si aucune erreur, procéder à l'insertion
        if (empty($response['errors'])) {
            // Hasher le mot de passe
            $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            // Insertion en base
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, date_naissance, mot_de_passe) VALUES (:nom, :prenom, :email, :telephone, :date_naissance, :mot_de_passe)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Inscription réussie !";
            } else {
                $response['errors']['general'] = "Erreur lors de l'inscription.";
            }
        }
    } else {
        $response['errors']['general'] = "Requête non autorisée.";
    }
} catch (PDOException $e) {
    $response['errors']['general'] = "Erreur SQL : " . $e->getMessage();
}

// Retourner la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>