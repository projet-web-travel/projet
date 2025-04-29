<?php
// controllers/ResetPasswordController.php

require(__DIR__ . '/../model/ResetPasswordModel.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require(__DIR__ . '/../PHPMailer/PHPMailer-6.10.0/src/Exception.php');
require(__DIR__ . '/../PHPMailer/PHPMailer-6.10.0/src/PHPMailer.php');
require(__DIR__ . '/../PHPMailer/PHPMailer-6.10.0/src/SMTP.php');

class ResetPasswordController {
    private $resetModel;
    private $db;

    public function __construct($database) {
        $this->db = $database;
        $this->resetModel = new ResetPasswordModel($database);
    }

    // Check if email exists in the database
    private function checkEmailExists($email) {
        try {
            $stmt = $this->db->prepare("SELECT `id` FROM `utilisateurs` WHERE `email` = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    // Function to process the reset request
    public function requestReset($email) {
        $result = [
            'success' => false,
            'message' => ''
        ];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "❌ Adresse email invalide.";
            return $result;
        }
        
        // Check if the email exists
        $user = $this->checkEmailExists($email);
        if (!$user) {
            $result['message'] = "❌ Adresse email non trouvée.";
            return $result;
        }

        // Generate a 4-digit code
        $code = sprintf("%04d", rand(0, 9999));

        // Calculate expiration time (30 minutes)
        $expirationTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        // Create or update reset request
        if ($this->createResetRequest($user['id'], $code, $expirationTime)) {
            // Send reset email
            if ($this->sendResetEmail($email, $code)) {
                $result['success'] = true;
                $result['message'] = "✅ Un code de réinitialisation a été envoyé à votre adresse email.";
            } else {
                $result['message'] = "❌ Erreur lors de l'envoi de l'email. Veuillez réessayer.";
            }
        } else {
            $result['message'] = "❌ Erreur lors de la création de la demande de réinitialisation.";
        }

        return $result;
    }

    // Create or update reset request
    private function createResetRequest($userId, $code, $expirationTime) {
        try {
            // Supprimons d'abord toute demande existante pour cet utilisateur
            $deleteStmt = $this->db->prepare("DELETE FROM reset_password WHERE Id_user = :user_id");
            $deleteStmt->bindParam(':user_id', $userId);
            $deleteStmt->execute();
            
            // Créons une nouvelle demande
            $stmt = $this->db->prepare("INSERT INTO reset_password (Id_user, code, expiration_time) VALUES (:user_id, :code, :expiration_time)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':expiration_time', $expirationTime);
            
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("Échec de l'opération SQL: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur PDO lors de la création de la demande: " . $e->getMessage());
            return false;
        }
    }

    // Function to send the reset email
    private function sendResetEmail($email, $code) {
        $mail = new PHPMailer(true);

        try {
            // Server configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'chouaibamdouni1@gmail.com';  // Your Gmail address
            $mail->Password = 'fwhjobnqzvixbnot';  // Your app-specific password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Enable verbose debugging
            $mail->SMTPDebug = 0;  // Changed from 2 to 0 for production (set to 2 for debugging)

            // Recipients
            $mail->setFrom('ne-pas-repondre@votre-domaine.com', 'Service de réinitialisation');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de mot de passe";
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .code { font-size: 24px; font-weight: bold; color: #007bff; }
                        .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Réinitialisation de mot de passe</h2>
                        <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                        <p>Votre code de réinitialisation est : <span class='code'>$code</span></p>
                        <p>Ce code expirera dans 30 minutes.</p>
                        <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = "Votre code de réinitialisation de mot de passe est : $code. Ce code expirera dans 30 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Erreur PHPMailer: " . $mail->ErrorInfo);
            return false;
        }
    }

    // Validate reset code
    private function validateResetCode($userId, $code) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reset_password WHERE Id_user = :user_id AND code = :code AND expiration_time > NOW()");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur PDO lors de la validation du code: " . $e->getMessage());
            return false;
        }
    }

    // Update user password
    private function updatePassword($userId, $newPassword) {
        try {
            $stmt = $this->db->prepare("UPDATE utilisateurs SET mot_de_passe = :password WHERE id = :user_id");
            $stmt->bindParam(':password', $newPassword);
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur PDO lors de la mise à jour du mot de passe: " . $e->getMessage());
            return false;
        }
    }

    // Delete reset request
    private function deleteResetRequest($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reset_password WHERE Id_user = :user_id");
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur PDO lors de la suppression de la demande: " . $e->getMessage());
            return false;
        }
    }

    // Function to verify the code and reset the password
    public function verifyAndReset($email, $code, $newPassword, $confirmPassword) {
        $result = [
            'success' => false,
            'message' => ''
        ];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "❌ Adresse email invalide.";
            return $result;
        }

        // Verify if passwords match
        if ($newPassword !== $confirmPassword) {
            $result['message'] = "❌ Les mots de passe ne correspondent pas.";
            return $result;
        }

        // Verify password strength
        if (strlen($newPassword) < 8) {
            $result['message'] = "❌ Le mot de passe doit contenir au moins 8 caractères.";
            return $result;
        }

        // Retrieve the user
        $user = $this->checkEmailExists($email);

        if (!$user) {
            $result['message'] = "❌ Adresse email non trouvée.";
            return $result;
        }

        // Validate the reset code
        $resetRequest = $this->validateResetCode($user['id'], $code);

        if (!$resetRequest) {
            $result['message'] = "❌ Code de réinitialisation invalide ou expiré.";
            return $result;
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password
        if ($this->updatePassword($user['id'], $hashedPassword)) {
            // Delete the reset request
            $this->deleteResetRequest($user['id']);

            $result['success'] = true;
            $result['message'] = "✅ Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.";
        } else {
            $result['message'] = "❌ Erreur lors de la mise à jour du mot de passe.";
        }

        return $result;
    }
}
?>