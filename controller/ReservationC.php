<?php
require_once '../Config.php';

class ReservationC
{
    // Ajouter une nouvelle réservation
    public function ajouterReservation($reservationData)
    {
        $nomClient = $reservationData['clientName'];
        $emailClient = $reservationData['clientEmail'];
        $telephoneClient = isset($reservationData['clientPhone']) ? $reservationData['clientPhone'] : null;
        $dateResa = $reservationData['reservationDate'];
        $idEvenement = $reservationData['eventId'];

        try {
            $sql = "INSERT INTO reservations 
                    (NomClient, EmailClient, TelephoneClient, DateReservation, IdEvenement)
                    VALUES
                    (:nom, :email, :telephone, :dateResa, :idEvt)";

            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $nomClient,
                ':email' => $emailClient,
                ':telephone' => $telephoneClient,
                ':dateResa' => $dateResa,
                ':idEvt' => $idEvenement
            ]);

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error while adding reservation: " . $e->getMessage());
        }
    }

    // Récupérer toutes les réservations
    public function afficherReservations()
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT r.Id, r.NomClient, r.EmailClient, r.TelephoneClient,
                           r.DateReservation, r.IdEvenement,
                           e.Nom AS EvenementNom
                    FROM reservations r
                    LEFT JOIN evenements e ON r.IdEvenement = e.Id";
            $stmt = $db->prepare($sql);
            $stmt->execute();

            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $reservations;
        } catch (PDOException $e) {
            throw new Exception("Error while retrieving reservations: " . $e->getMessage());
        }
    }

    // Modifier une réservation existante
    public function modifierReservation($data)
    {
        try {
            $db = config::getConnexion();
            $id = $data['id'];
            $nom = $data['clientName'];
            $email = $data['clientEmail'];
            $tel = isset($data['clientPhone']) ? $data['clientPhone'] : null;
            $dateResa = $data['reservationDate'];
            $idEvt = $data['eventId'];

            $sql = "UPDATE reservations SET 
                        NomClient       = :nom,
                        EmailClient     = :email,
                        TelephoneClient = :tel,
                        DateReservation = :dateResa,
                        IdEvenement     = :idEvt
                    WHERE Id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':dateResa', $dateResa);
            $stmt->bindParam(':idEvt', $idEvt, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error during reservation update: " . $e->getMessage());
        }
    }

    // Supprimer une réservation
    public function supprimerReservation($id)
    {
        try {
            $db = config::getConnexion();

            $sql = "DELETE FROM reservations WHERE Id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'ID not found'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Server error: ' . $e->getMessage()];
        }
    }
}
?>