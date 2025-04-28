<?php
require_once '../Config.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$db = config::getConnexion();
$stmt = $db->prepare("SELECT COUNT(*) FROM evenements WHERE Id = :id");
$stmt->execute([':id' => $id]);
$exists = (int) $stmt->fetchColumn() > 0;

echo json_encode(['exists' => $exists]);
