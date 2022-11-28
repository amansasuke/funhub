<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $MangerID;

    /**
     * @ORM\Column(type="integer")
     */
    private $ClientId;

    /**
     * @ORM\Column(type="date")
     */
    private $StartDate;

    /**
     * @ORM\Column(type="date")
     */
    private $EndDate;

    /**
     * @ORM\Column(type="time")
     */
    private $StartTime;

    /**
     * @ORM\Column(type="time")
     */
    private $EndTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $MangerStart;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ClientStartDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $ClientStartTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $ClientEndTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ClientStatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $BookingStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMangerID(): ?int
    {
        return $this->MangerID;
    }

    public function setMangerID(int $MangerID): self
    {
        $this->MangerID = $MangerID;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->ClientId;
    }

    public function setClientId(int $ClientId): self
    {
        $this->ClientId = $ClientId;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->StartDate;
    }

    public function setStartDate(\DateTimeInterface $StartDate): self
    {
        $this->StartDate = $StartDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->EndDate;
    }

    public function setEndDate(\DateTimeInterface $EndDate): self
    {
        $this->EndDate = $EndDate;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->StartTime;
    }

    public function setStartTime(\DateTimeInterface $StartTime): self
    {
        $this->StartTime = $StartTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->EndTime;
    }

    public function setEndTime(\DateTimeInterface $EndTime): self
    {
        $this->EndTime = $EndTime;

        return $this;
    }

    public function getMangerStart(): ?string
    {
        return $this->MangerStart;
    }

    public function setMangerStart(string $MangerStart): self
    {
        $this->MangerStart = $MangerStart;

        return $this;
    }

    public function getClientStartDate(): ?\DateTimeInterface
    {
        return $this->ClientStartDate;
    }

    public function setClientStartDate(?\DateTimeInterface $ClientStartDate): self
    {
        $this->ClientStartDate = $ClientStartDate;

        return $this;
    }

    public function getClientStartTime(): ?\DateTimeInterface
    {
        return $this->ClientStartTime;
    }

    public function setClientStartTime(?\DateTimeInterface $ClientStartTime): self
    {
        $this->ClientStartTime = $ClientStartTime;

        return $this;
    }

    public function getClientEndTime(): ?\DateTimeInterface
    {
        return $this->ClientEndTime;
    }

    public function setClientEndTime(?\DateTimeInterface $ClientEndTime): self
    {
        $this->ClientEndTime = $ClientEndTime;

        return $this;
    }

    public function getClientStatus(): ?string
    {
        return $this->ClientStatus;
    }

    public function setClientStatus(string $ClientStatus): self
    {
        $this->ClientStatus = $ClientStatus;

        return $this;
    }

    public function getBookingStatus(): ?string
    {
        return $this->BookingStatus;
    }

    public function setBookingStatus(string $BookingStatus): self
    {
        $this->BookingStatus = $BookingStatus;

        return $this;
    }
}
