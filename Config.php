<?php
class Database {
    private $host = "localhost";
    private $db_name = "agence_voyage";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("SET NAMES utf8");
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>

<?php
require_once 'config.php';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "✅ Connexion réussie à la base de données.";
} else {
    echo "❌ Échec de la connexion à la base de données.";
}
?>
