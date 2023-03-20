<?php

namespace App\Entity;

use App\Repository\DocForClientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocForClientRepository::class)
 */
class DocForClient
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
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Doclink;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="docForClients")
     */
    private $Ordername;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Status;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updateat;

    public function __construct()
    {
      
        $this->updateat= new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDoclink(): ?string
    {
        return $this->Doclink;
    }

    public function setDoclink(string $Doclink): self
    {
        $this->Doclink = $Doclink;

        return $this;
    }

    public function getOrdername(): ?Order
    {
        return $this->Ordername;
    }

    public function setOrdername(?Order $Ordername): self
    {
        $this->Ordername = $Ordername;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getUpdateat(): ?\DateTimeInterface
    {
        return $this->updateat;
    }

    public function setUpdateat(?\DateTimeInterface $updateat): self
    {
        $this->updateat = new \DateTime();

        return $this;
    }
}
