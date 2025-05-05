<?php
require_once '../controller/ReservationC.php';

$resC = new ReservationC();
$reservations = $resC->afficherReservations();

foreach ($reservations as $row) {
    echo "<tr>
        <td>{$row['Id']}</td>
        <td>{$row['IdEvenement']}</td>
        <td>{$row['EvenementNom']}</td>
        <td>{$row['NomClient']}</td>
        <td>{$row['EmailClient']}</td>
        <td>{$row['TelephoneClient']}</td>
        <td>{$row['DateReservation']}</td>
        <td>
            <button class='icon-btn delete-btn' data-id='{$row['Id']}'><i class='fas fa-trash'></i></button>
            <button class='icon-btn edit-btn' data-id='{$row['Id']}'><i class='fa-solid fa-pen-to-square'></i></button>
        </td>
    </tr>";
}
?>