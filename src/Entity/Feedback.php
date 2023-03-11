<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeedbackRepository::class)
 */
class Feedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $proname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $disreviwe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reating;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProname(): ?string
    {
        return $this->proname;
    }

    public function setProname(?string $proname): self
    {
        $this->proname = $proname;

        return $this;
    }

    public function getOrderid(): ?string
    {
        return $this->orderid;
    }

    public function setOrderid(?string $orderid): self
    {
        $this->orderid = $orderid;

        return $this;
    }

    public function getDisreviwe(): ?string
    {
        return $this->disreviwe;
    }

    public function setDisreviwe(?string $disreviwe): self
    {
        $this->disreviwe = $disreviwe;

        return $this;
    }

    public function getReating(): ?int
    {
        return $this->reating;
    }

    public function setReating(?int $reating): self
    {
        $this->reating = $reating;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserid(): ?string
    {
        return $this->userid;
    }

    public function setUserid(?string $userid): self
    {
        $this->userid = $userid;

        return $this;
    }
}
