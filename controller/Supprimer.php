<?php
require_once '../controller/EventC.php';

$eventC = new EventC();
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    $result = $eventC->supprimerEvenement($id);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'ID not valid']);
}
?>
