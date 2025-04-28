<?php

class Reservation
{
    private ?int $id = null;
    private ?string $clientName = null;
    private ?string $clientEmail = null;
    private ?string $clientPhone = null;
    private ?int $numSeats = null;
    private ?string $reservationDate = null;
    private ?int $eventId = null;

    public function __construct(
        $id = null,
        $clientName,
        $clientEmail,
        $clientPhone,
        $numSeats,
        $reservationDate,
        $eventId
    ) {
        $this->id = $id;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->clientPhone = $clientPhone;
        $this->numSeats = $numSeats;
        $this->reservationDate = $reservationDate;
        $this->eventId = $eventId;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function getClientPhone(): ?string
    {
        return $this->clientPhone;
    }

    public function getNumSeats(): ?int
    {
        return $this->numSeats;
    }

    public function getReservationDate(): ?string
    {
        return $this->reservationDate;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    // Setters
    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;
        return $this;
    }

    public function setClientEmail(string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;
        return $this;
    }

    public function setClientPhone(?string $clientPhone): self
    {
        $this->clientPhone = $clientPhone;
        return $this;
    }

    public function setNumSeats(int $numSeats): self
    {
        $this->numSeats = $numSeats;
        return $this;
    }

    public function setReservationDate(string $reservationDate): self
    {
        $this->reservationDate = $reservationDate;
        return $this;
    }

    public function setEventId(int $eventId): self
    {
        $this->eventId = $eventId;
        return $this;
    }
}
