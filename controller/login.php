<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include configuration and database connection
require_once '../config.php';

try {
    // Create a database connection
    $db = new Database();
    $pdo = $db->getConnection();

    // Initialize response
    $response = [
        'success' => false,
        'message' => '',
        'errors' => []
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get and sanitize input
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['mot_de_passe'] ?? '');

        // Validate email
        if (empty($email)) {
            $response['errors']['email'] = "L'email est requis.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['errors']['email'] = "Format d'email invalide.";
        }

        // Validate password
        if (empty($password)) {
            $response['errors']['mot_de_passe'] = "Le mot de passe est requis.";
        }

        // If no errors, attempt login
        if (empty($response['errors'])) {
            // Query to check if the user exists
            $query = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                error_log("User found: " . print_r($user, true));

                // Verify password (plain text comparison)
                if ($password === $user['mot_de_passe']) {
                    // Start session and store user data
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['email'] = $user['email'];

                    $response['success'] = true;
                    $response['message'] = "Connexion réussie.";
                } else {
                    error_log("Password mismatch: Entered = $password, Stored = " . $user['mot_de_passe']);
                    $response['errors']['general'] = "Email ou mot de passe incorrect.";
                }
            } else {
                error_log("No user found with email: $email");
                $response['errors']['general'] = "Email ou mot de passe incorrect.";
            }
        }
    } else {
        $response['errors']['general'] = "Requête non autorisée.";
    }
} catch (PDOException $e) {
    $response['errors']['general'] = "Erreur de base de données: " . $e->getMessage();
} catch (Exception $e) {
    $response['errors']['general'] = "Erreur: " . $e->getMessage();
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>