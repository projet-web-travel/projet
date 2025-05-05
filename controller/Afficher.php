<?php
require_once '../controller/EventC.php';

$eventC = new EventC();
$evenements = $eventC->afficherEvenements();

foreach ($evenements as $row) {
    $status = strtolower($row['Status']);
    $statusClass = $status === 'active' ? 'active' : 'paused';

    echo "<tr>
        <td>{$row['Id']}</td>
        <td><img src='../uploads/{$row['Apercu']}' alt='Aperçu' style='width: 60px; height: auto;'></td>
        <td>{$row['Nom']}</td>
        <td>{$row['Date']}</td>
        <td>{$row['Prix']}</td>
        <td>{$row['Duree']}</td>
        <td>{$row['Localisation']}</td>
        <td>{$row['NombrePlaces']}</td>
        <td><span class='status {$statusClass}'>" . ucfirst($row['Status']) . "</span></td>
        <td>
            <button class='icon-btn delete-btn' data-id='{$row['Id']}'><i class='fas fa-trash'></i></button>
            <button class='icon-btn edit-btn' data-id='{$row['Id']}'><i class='fa-solid fa-pen-to-square'></i></button>
        </td>
    </tr>";
}
?>