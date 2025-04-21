<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'agence_voyage';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        // Mettre à jour le client dans la base de données
        $sql = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, 
                telephone = :telephone, email = :email, mot_de_passe = :mot_de_passe WHERE Id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $date_naissance,
            'telephone' => $telephone,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe
        ]);

        // Rediriger vers la page des clients avec un message de succès
        header('Location: customers.php?updated=1');
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}
?>
