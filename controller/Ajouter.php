<?php
require_once '../controller/EventC.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $eventC = new EventC();

    try {
        $eventC->ajouterEvenement($_POST, $_FILES);
        echo "✅ Event added successfully.";
        echo "<br><a href='../view/dashboard-event.php'>Return</a>";
    } catch (Exception $e) {
        echo "❌ " . $e->getMessage();
    }
}
?>
