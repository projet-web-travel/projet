<?php
header('Content-Type: application/json');

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "mon_projet");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Récupération des événements
$sql = "SELECT Apercu, Nom, Date, Prix, Duree, Localisation FROM evenements WHERE Status = 'Active'";
$result = $conn->query($sql);

$events = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode($events);
$conn->close();
?>
