<?php
require_once '../controller/ReservationC.php';

$reservationC = new ReservationC();

try {
    // Apply the modifications using the posted form fields
    $reservationC->modifierReservation($_POST);
    // Redirect to the reservations list on success
    header("Location: ../view/dashboard-reservations.php");
    exit();
} catch (Exception $e) {
    // Display any errors that occurred
    echo "❌ " . $e->getMessage();
}
?>