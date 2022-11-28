<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
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
    private $Userid;

    /**
     * @ORM\Column(type="date")
     */
    private $Starttime;

    /**
     * @ORM\Column(type="date")
     */
    private $Endtime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Startslot;

    /**
     * @ORM\Column(type="time")
     */
    private $StartSlot;

    /**
     * @ORM\Column(type="time")
     */
    private $StartSlotM;

    /**
     * @ORM\Column(type="time")
     */
    private $EndSlotM;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $MangetStatu;

    /**
     * @ORM\Column(type="date")
     */
    private $Clintstarttime;

    /**
     * @ORM\Column(type="date")
     */
    private $ClintEndtime;

    /**
     * @ORM\Column(type="time")
     */
    private $ClintStartSlot;

    /**
     * @ORM\Column(type="time")
     */
    private $ClintEndSlot;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserid(): ?int
    {
        return $this->Userid;
    }

    public function setUserid(int $Userid): self
    {
        $this->Userid = $Userid;

        return $this;
    }

    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->Starttime;
    }

    public function setStarttime(\DateTimeInterface $Starttime): self
    {
        $this->Starttime = $Starttime;

        return $this;
    }

    public function getEndtime(): ?\DateTimeInterface
    {
        return $this->Endtime;
    }

    public function setEndtime(\DateTimeInterface $Endtime): self
    {
        $this->Endtime = $Endtime;

        return $this;
    }

    public function getStartslot(): ?string
    {
        return $this->Startslot;
    }

    public function setStartslot(string $Startslot): self
    {
        $this->Startslot = $Startslot;

        return $this;
    }

    public function getStartSlotM(): ?\DateTimeInterface
    {
        return $this->StartSlotM;
    }

    public function setStartSlotM(\DateTimeInterface $StartSlotM): self
    {
        $this->StartSlotM = $StartSlotM;

        return $this;
    }

    public function getEndSlotM(): ?\DateTimeInterface
    {
        return $this->EndSlotM;
    }

    public function setEndSlotM(\DateTimeInterface $EndSlotM): self
    {
        $this->EndSlotM = $EndSlotM;

        return $this;
    }

    public function getMangetStatu(): ?string
    {
        return $this->MangetStatu;
    }

    public function setMangetStatu(string $MangetStatu): self
    {
        $this->MangetStatu = $MangetStatu;

        return $this;
    }

    public function getClintstarttime(): ?\DateTimeInterface
    {
        return $this->Clintstarttime;
    }

    public function setClintstarttime(\DateTimeInterface $Clintstarttime): self
    {
        $this->Clintstarttime = $Clintstarttime;

        return $this;
    }

    public function getClintEndtime(): ?\DateTimeInterface
    {
        return $this->ClintEndtime;
    }

    public function setClintEndtime(\DateTimeInterface $ClintEndtime): self
    {
        $this->ClintEndtime = $ClintEndtime;

        return $this;
    }

    public function getClintStartSlot(): ?\DateTimeInterface
    {
        return $this->ClintStartSlot;
    }

    public function setClintStartSlot(\DateTimeInterface $ClintStartSlot): self
    {
        $this->ClintStartSlot = $ClintStartSlot;

        return $this;
    }

    public function getClintEndSlot(): ?\DateTimeInterface
    {
        return $this->ClintEndSlot;
    }

    public function setClintEndSlot(\DateTimeInterface $ClintEndSlot): self
    {
        $this->ClintEndSlot = $ClintEndSlot;

        return $this;
    }
}
