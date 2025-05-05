<?php
require_once '../controller/ReservationC.php';

$reservationC = new ReservationC();

try {
    $reservationC->modifierReservation($_POST);
    header("Location: ../view/dashboard-reservations.php");
    exit();
} catch (Exception $e) {
    echo "❌ " . $e->getMessage();
}
?>