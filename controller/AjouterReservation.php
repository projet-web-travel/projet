<?php
require_once '../controller/ReservationC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservationC = new ReservationC();

    try {
        $reservationC->ajouterReservation($_POST);
        echo "✅ Reservation added successfully.";
        echo "<br><a href='../view/dashboard-reservations.php'>Return</a>";
    } catch (Exception $e) {
        echo "❌ " . $e->getMessage();
    }
}
?>