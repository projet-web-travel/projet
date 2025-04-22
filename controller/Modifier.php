<?php
require_once '../controller/EventC.php';

$eventC = new EventC();

try {
    $eventC->modifierEvenement($_POST, $_FILES);
    header("Location: ../view/dashboard-event.php");
    exit();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
