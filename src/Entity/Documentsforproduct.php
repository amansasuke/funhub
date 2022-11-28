<?php

namespace App\Entity;

use App\Repository\DocumentsforproductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentsforproductRepository::class)
 */
class Documentsforproduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="documentsforproducts")
     */
    private $productinfo;

    /**
     * @ORM\ManyToOne(targetEntity=Doctype::class, inversedBy="documentsforproducts")
     */
    private $docinfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductinfo(): ?Product
    {
        return $this->productinfo;
    }

    public function setProductinfo(?Product $productinfo): self
    {
        $this->productinfo = $productinfo;

        return $this;
    }

    public function getDocinfo(): ?Doctype
    {
        return $this->docinfo;
    }

    public function setDocinfo(?Doctype $docinfo): self
    {
        $this->docinfo = $docinfo;

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
    public function __toString() {
        return $this->docinfo;
        return $this->productinfo;

    }


}
