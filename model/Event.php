<?php

class Event
{
    private ?int $eventId = null;
    private ?string $preview = null;
    private ?string $name = null;
    private ?string $date = null;
    private ?float $price = null;
    private ?string $duration = null;
    private ?string $location = null;
    private ?string $status = null;

    public function __construct(
        $eventId = null,
        $preview,
        $name,
        $date,
        $price,
        $duration,
        $location,
        $status
    ) {
        $this->eventId = $eventId;
        $this->preview = $preview;
        $this->name = $name;
        $this->date = $date;
        $this->price = $price;
        $this->duration = $duration;
        $this->location = $location;
        $this->status = $status;
    }

    // Getters
    public function getEventId()
    {
        return $this->eventId;
    }

    public function getPreview()
    {
        return $this->preview;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Setters
    public function setPreview($preview)
    {
        $this->preview = $preview;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}