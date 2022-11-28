<?php

namespace App\Entity;

use App\Repository\EventbookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventbookingRepository::class)
 */
class Eventbooking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $startdate;

    /**
     * @ORM\Column(type="date")
     */
    private $bookingstart;

    /**
     * @ORM\Column(type="time")
     */
    private $bookingtime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $userid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usermail;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventbookings")
     */
    private $manger;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meetinglink;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDis(): ?string
    {
        return $this->dis;
    }

    public function setDis(string $dis): self
    {
        $this->dis = $dis;

        return $this;
    }

    public function getStartdate(): ?string
    {
        return $this->startdate;
    }

    public function setStartdate(?string $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getBookingstart(): ?\DateTimeInterface
    {
        return $this->bookingstart;
    }

    public function setBookingstart(\DateTimeInterface $bookingstart): self
    {
        $this->bookingstart = $bookingstart;

        return $this;
    }

    public function getBookingtime(): ?\DateTimeInterface
    {
        return $this->bookingtime;
    }

    public function setBookingtime(\DateTimeInterface $bookingtime): self
    {
        $this->bookingtime = $bookingtime;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getUsermail(): ?string
    {
        return $this->usermail;
    }

    public function setUsermail(string $usermail): self
    {
        $this->usermail = $usermail;

        return $this;
    }

    public function getManger(): ?User
    {
        return $this->manger;
    }

    public function setManger(?User $manger): self
    {
        $this->manger = $manger;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMeetinglink(): ?string
    {
        return $this->meetinglink;
    }

    public function setMeetinglink(?string $meetinglink): self
    {
        $this->meetinglink = $meetinglink;

        return $this;
    }
}
