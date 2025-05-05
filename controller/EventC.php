<?php
require_once '../Config.php';

class EventC
{
    public function ajouterEvenement($eventData, $file)
{
    $nom = $eventData['eventName'];
    $date = $eventData['eventDate'];
    $prix = $eventData['eventPrice'];
    $duree = $eventData['eventDuration'];
    $localisation = $eventData['eventLocation'];
    $status = $eventData['event-status'];
    $places = $eventData['eventCapacity'];


    if (isset($file['event-preview']) && $file['event-preview']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $file['event-preview']['tmp_name'];
        $image_name = basename($file['event-preview']['name']);
        $target_dir = "C:/xampp/htdocs/Projet/uploads/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($image_tmp, $target_file)) {
            $apercu = $image_name;
        } else {
            throw new Exception("Error while uploading image.");
        }
    } else {
        throw new Exception("No image uploaded or error.");
    }

    // Requête d'insertion
    try {
        $sql = "INSERT INTO evenements (Apercu, Nom, Date, Prix, Duree, Localisation, Status, NombrePlaces)
                VALUES (:apercu, :nom, :date, :prix, :duree, :localisation, :status, :places)";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':apercu' => $apercu,
            ':nom' => $nom,
            ':date' => $date,
            ':prix' => $prix,
            ':duree' => $duree,
            ':localisation' => $localisation,
            ':status' => $status,
            ':places' => $places
        ]);
    } catch (PDOException $e) {
        throw new Exception("Error while adding : " . $e->getMessage());
    }
}

public function afficherEvenements()
{
    try {
        $db = config::getConnexion();
        $sql = "SELECT * FROM evenements";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $evenements;
    } catch (PDOException $e) {
        throw new Exception("Error while retrieving events : " . $e->getMessage());
    }
}

public function modifierEvenement($data, $file)
{
    try {
        $db = config::getConnexion();

        $id = $data['id'];
        $name = $data['eventName'];
        $date = $data['eventDate'];
        $price = $data['eventPrice'];
        $duration = $data['eventDuration'];
        $location = $data['eventLocation'];
        $status = $data['eventStatus'];
        $places = $data['eventCapacity'];

        $previewSet = "";
        if (isset($file['eventPreview']) && $file['eventPreview']['error'] === UPLOAD_ERR_OK) {
            $fileName = $file['eventPreview']['name'];
            $fileTmp = $file['eventPreview']['tmp_name'];
            $destination = '../uploads/' . $fileName;

            move_uploaded_file($fileTmp, $destination);
            $previewSet = ", Apercu = :apercu";
        }

        $sql = "UPDATE evenements SET 
                    Nom = :nom,
                    Date = :date,
                    Prix = :prix,
                    Duree = :duree,
                    Localisation = :localisation,
                    Status = :status,
                    NombrePlaces = :places
                    $previewSet
                WHERE Id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nom', $name);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':prix', $price);
        $stmt->bindParam(':duree', $duration);
        $stmt->bindParam(':localisation', $location);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':places', $places, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!empty($previewSet)) {
            $stmt->bindParam(':apercu', $fileName);
        }

        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        throw new Exception("Error during update : " . $e->getMessage());
    }
}

public function supprimerEvenement($id)
{
    try {
        $db = config::getConnexion();

        $sql = "DELETE FROM evenements WHERE Id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'ID not found'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'error' => 'Server error : ' . $e->getMessage()];
    }
}

}
?>
