<?php
require_once(__DIR__ . '/../model/UserModel.php');
class UserController {
    private $model;
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        $this->model = new UserModel($db);
    }

    // Create new user
    public function create() {
        // Hash password
        $hashed_password = password_hash($this->model->getMotDePasse(), PASSWORD_BCRYPT);
        $this->model->setMotDePasse($hashed_password);
        // Query to insert data
        $query = "INSERT INTO utilisateurs (
                    nom, prenom, email, mot_de_passe, telephone, date_naissance
                ) VALUES (
                    :nom, :prenom, :email, :mot_de_passe, :telephone, :date_naissance
                )";
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind values
        $stmt->bindParam(":nom", $this->model->getNom());
        $stmt->bindParam(":prenom", $this->model->getPrenom());
        $stmt->bindParam(":email", $this->model->getEmail());
        $stmt->bindParam(":mot_de_passe", $this->model->getMotDePasse());
        $stmt->bindParam(":telephone", $this->model->getTelephone());
        $stmt->bindParam(":date_naissance", $this->model->getDateNaissance());
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Check if user exists and verify password
    public function login() {
        // Query to check user exists
        $query = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 0,1";
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind value
        $stmt->bindParam(":email", $this->model->getEmail());
        // Execute query
        $stmt->execute();
        // Get row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if email exists, if yes then verify password
        if ($row) {
            $this->model->setId($row['Id']);
            $this->model->setNom($row['nom']);
            $this->model->setPrenom($row['prenom']);
            $this->model->setEmail($row['email']);
            $saved_password = $row['mot_de_passe'];
            $this->model->setTelephone($row['telephone']);
            $this->model->setDateNaissance($row['date_naissance']);
            // Verify password
            if (password_verify($this->model->getMotDePasse(), $saved_password)) {
                return true;
            }
        }
        return false;
    }

    // Check if email already exists
    public function checkEmailExists($email) {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    // Check if email already exists for another user
    public function checkEmailExistsForOtherUser($email, $id) {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE email = :email AND Id <> :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    // Get all users
    public function getAllCustomers() {
        $query = "SELECT * FROM utilisateurs ORDER BY nom";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update user
    public function update() {
        // Hash password if changed
        if (!empty($this->model->getMotDePasse())) {
            $hashed_password = password_hash($this->model->getMotDePasse(), PASSWORD_BCRYPT);
            $this->model->setMotDePasse($hashed_password);
            $password_set = true;
        } else {
            $password_set = false;
        }
        // Query to update data
        $query = "UPDATE utilisateurs SET
                    nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    ";
        if ($password_set) {
            $query .= "mot_de_passe = :mot_de_passe,";
        }
        $query .= "
                    telephone = :telephone,
                    date_naissance = :date_naissance
                WHERE Id = :id";
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind values
        $stmt->bindParam(":nom", $this->model->getNom());
        $stmt->bindParam(":prenom", $this->model->getPrenom());
        $stmt->bindParam(":email", $this->model->getEmail());
        if ($password_set) {
            $stmt->bindParam(":mot_de_passe", $this->model->getMotDePasse());
        }
        $stmt->bindParam(":telephone", $this->model->getTelephone());
        $stmt->bindParam(":date_naissance", $this->model->getDateNaissance());
        $stmt->bindParam(":id", $this->model->getId());
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete user
    public function delete() {
        // Query to delete data
        $query = "DELETE FROM utilisateurs WHERE Id = :id";
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Bind value
        $stmt->bindParam(":id", $this->model->getId());
        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Handle user registration
    public function register($data) {
        $this->model->setNom(htmlspecialchars(strip_tags($data['nom'])));
        $this->model->setPrenom(htmlspecialchars(strip_tags($data['prenom'])));
        $this->model->setEmail(htmlspecialchars(strip_tags($data['email'])));
        $this->model->setMotDePasse(htmlspecialchars(strip_tags($data['mot_de_passe'])));
        $this->model->setTelephone(htmlspecialchars(strip_tags($data['telephone'])));
        $this->model->setDateNaissance(htmlspecialchars(strip_tags($data['date_naissance'])));
        // Check if email already exists
        if ($this->checkEmailExists($this->model->getEmail())) {
            return "Email déjà enregistré.";
        }
        // Create new user
        if ($this->create()) {
            return "Inscription réussie.";
        }
        return "Erreur lors de l'inscription.";
    }

    // Handle user login
    public function loginUser($data) {
        $this->model->setEmail(htmlspecialchars(strip_tags($data['email'])));
        $this->model->setMotDePasse(htmlspecialchars(strip_tags($data['mot_de_passe'])));
        // Check if user exists and verify password
        if ($this->login()) {
            return "Connexion réussie.";
        }
        return "Email ou mot de passe incorrect.";
    }

    // Get customer by ID
    public function getCustomerById($id) {
        $query = "SELECT * FROM utilisateurs WHERE Id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->model->setId($row['Id']);
            $this->model->setNom($row['nom']);
            $this->model->setPrenom($row['prenom']);
            $this->model->setEmail($row['email']);
            $this->model->setMotDePasse($row['mot_de_passe']);
            $this->model->setTelephone($row['telephone']);
            $this->model->setDateNaissance($row['date_naissance']);
            return $this->model;
        }
        return null;
    }

    // Update customer
    public function updateUser($data) {
        $this->model->setId(htmlspecialchars(strip_tags($data['id'])));
        $this->model->setNom(htmlspecialchars(strip_tags($data['nom'])));
        $this->model->setPrenom(htmlspecialchars(strip_tags($data['prenom'])));
        $this->model->setEmail(htmlspecialchars(strip_tags($data['email'])));
        $this->model->setMotDePasse(isset($data['mot_de_passe']) ? htmlspecialchars(strip_tags($data['mot_de_passe']))  :  '');
        $this->model->setTelephone(htmlspecialchars(strip_tags($data['telephone'])));
        $this->model->setDateNaissance(htmlspecialchars(strip_tags($data['date_naissance'])));
        // Check if email already exists for another user
        if ($this->checkEmailExistsForOtherUser($this->model->getEmail(), $this->model->getId())) {
            return "Email déjà enregistré pour un autre utilisateur.";
        }
        // Update user
        if ($this->update()) {
            return "Mise à jour réussie.";
        }
        return "Erreur lors de la mise à jour.";
    }

    // Delete customer
    public function deleteUser($id) {
        $this->model->setId(htmlspecialchars(strip_tags($id)));
        // Delete user
        if ($this->delete()) {
            return "Suppression réussie.";
        }
        return "Erreur lors de la suppression.";
    }
}
?> 