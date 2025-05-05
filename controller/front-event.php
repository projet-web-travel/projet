<?php
header('Content-Type: application/json');
require_once '../Config.php';

try {
    $pdo = Config::getConnexion();
    $sql = "
        SELECT 
            Id,
            Apercu,
            Nom,
            Date,
            Prix,
            Duree,
            Localisation,
            NombrePlaces
        FROM evenements
        WHERE Status = 'Active'
    ";
    $stmt = $pdo->query($sql);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);
} catch (Exception $e) {
    // On error, return an empty array (or you could return ['error' => $e->getMessage()])
    echo json_encode([]);
}
