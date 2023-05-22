<?php

namespace App\Entity;

use App\Repository\PromoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
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
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $discount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

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

    public function getUserid(): ?string
    {
        return $this->userid;
    }

    public function setUserid(?string $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
