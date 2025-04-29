<?php
// models/ResetPasswordModel.php

class ResetPasswordModel {
    private $db;
    private $userId;
    private $code;
    private $expirationTime;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Getters
    public function getUserId() {
        return $this->userId;
    }
    
    public function getCode() {
        return $this->code;
    }
    
    public function getExpirationTime() {
        return $this->expirationTime;
    }
    
    public function getDb() {
        return $this->db;
    }
    
    // Setters
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    
    public function setCode($code) {
        $this->code = $code;
    }
    
    public function setExpirationTime($expirationTime) {
        $this->expirationTime = $expirationTime;
    }
}
?>