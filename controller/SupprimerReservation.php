<?php
require_once '../controller/ReservationC.php';

$reservationC = new ReservationC();
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    $result = $reservationC->supprimerReservation($id);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'ID not valid']);
}
?>