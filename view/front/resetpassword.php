<?php
// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require ('../../config.php'); // Adjust this path to your config file
require_once(__DIR__ . '/../../controller/ResetPasswordController.php');

// Vérifier que la connexion à la base de données est établie
if (!isset($database) || $database === null) {
    try {
        // Create database connection if it doesn't exist
        $host = 'localhost'; // or your database host
        $dbname = 'agence_voyage'; // your database name
        $username = 'root'; // your database username
        $password = ''; // your database password
        
        $database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

// Créer l'instance du contrôleur
$resetController = new ResetPasswordController($database);

// Initialiser les variables
$step = isset($_GET['step']) ? $_GET['step'] : 'request';
$message = '';
$success = false;
$email = isset($_POST['email']) ? $_POST['email'] : (isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '');

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 'request' && isset($_POST['email'])) {
        // Traiter la demande de réinitialisation
        $result = $resetController->requestReset($_POST['email']);
        $message = $result['message'];
        $success = $result['success'];
        
        if ($success) {
            // Stocker l'email dans la session pour l'étape suivante
            $_SESSION['reset_email'] = $_POST['email'];
            // Rediriger vers l'étape de vérification
            header('Location: resetpassword.php?step=verify');
            exit;
        }
    } elseif ($step === 'verify' && isset($_POST['code']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        // Vérifier le code et réinitialiser le mot de passe
        $code = $_POST['code'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        $result = $resetController->verifyAndReset($email, $code, $newPassword, $confirmPassword);
        $message = $result['message'];
        $success = $result['success'];
        
        if ($success) {
            // Nettoyer la session
            unset($_SESSION['reset_email']);
            // Rediriger vers la page de connexion après un petit délai
            header('Refresh: 3; URL=connexion.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Réinitialisation de mot de passe</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($step === 'request'): ?>
            <!-- Étape 1: Demande de réinitialisation -->
            <p>Veuillez entrer votre adresse email pour recevoir un code de réinitialisation.</p>
            <form method="post" action="resetpassword.php?step=request">
                <div class="form-group">
                    <label for="email">Adresse email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <button type="submit" class="btn">Envoyer le code</button>
            </form>
            <p>Vous vous souvenez de votre mot de passe? <a href="connexion.php">Connectez-vous</a></p>
            
        <?php elseif ($step === 'verify'): ?>
            <!-- Étape 2: Vérification du code et réinitialisation du mot de passe -->
            <p>Un code de réinitialisation a été envoyé à <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
            <p>Veuillez entrer le code reçu et choisir un nouveau mot de passe.</p>
            <form method="post" action="resetpassword.php?step=verify">
                <div class="form-group">
                    <label for="code">Code de réinitialisation:</label>
                    <input type="text" id="code" name="code" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Réinitialiser le mot de passe</button>
            </form>
            <p><a href="resetpassword.php?step=request">Retour à l'étape précédente</a></p>
        <?php endif; ?>
    </div>
</body>
</html>