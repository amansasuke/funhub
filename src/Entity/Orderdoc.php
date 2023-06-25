<?php

namespace App\Entity;

use App\Repository\OrderdocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderdocRepository::class)
 */
class Orderdoc
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
    private $docname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $doclink;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, inversedBy="orderdocs")
     */
    private $orderid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $remark;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docremark;

    public function __construct()
    {
        $this->orderid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocname(): ?string
    {
        return $this->docname;
    }

    public function setDocname(string $docname): self
    {
        $this->docname = $docname;

        return $this;
    }

    public function getDoclink(): ?string
    {
        return $this->doclink;
    }

    public function setDoclink(string $doclink): self
    {
        $this->doclink = $doclink;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderid(): Collection
    {
        return $this->orderid;
    }

    public function addOrderid(Order $orderid): self
    {
        if (!$this->orderid->contains($orderid)) {
            $this->orderid[] = $orderid;
        }

        return $this;
    }

    public function removeOrderid(Order $orderid): self
    {
        $this->orderid->removeElement($orderid);

        return $this;
    }
    public function __toString() {
        return $this->orderid;
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

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function getDocremark(): ?string
    {
        return $this->docremark;
    }

    public function setDocremark(?string $docremark): self
    {
        $this->docremark = $docremark;

        return $this;
    }
}
