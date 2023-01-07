<?php

namespace App\Entity;

use App\Repository\DocforproRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocforproRepository::class)
 */
class Docforpro
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="doctinfo")
     */
    private $proinfo;

    /**
     * @ORM\ManyToMany(targetEntity=Doctype::class, inversedBy="docforpros")
     */
    private $newdocinfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->newdocinfo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProinfo(): ?Product
    {
        return $this->proinfo;
    }

    public function setProinfo(?Product $proinfo): self
    {
        $this->proinfo = $proinfo;

        return $this;
    }

    /**
     * @return Collection<int, Doctype>
     */
    public function getNewdocinfo(): Collection
    {
        return $this->newdocinfo;
    }

    public function addNewdocinfo(Doctype $newdocinfo): self
    {
        if (!$this->newdocinfo->contains($newdocinfo)) {
            $this->newdocinfo[] = $newdocinfo;
        }

        return $this;
    }

    public function removeNewdocinfo(Doctype $newdocinfo): self
    {
        $this->newdocinfo->removeElement($newdocinfo);

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
