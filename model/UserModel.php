<?php
require_once(__DIR__ . '/../config.php');


class UserModel {
    protected $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $telephone;
    public $date_naissance;

    // Constructeur
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMotDePasse() {
        return $this->mot_de_passe;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getDateNaissance() {
        return $this->date_naissance;
    }

    public function getConn() {
        return $this->conn;
    }

    public function getTableName() {
        return $this->table_name;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMotDePasse($mot_de_passe) {
        $this->mot_de_passe = $mot_de_passe;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setDateNaissance($date_naissance) {
        $this->date_naissance = $date_naissance;
    }
}
?>