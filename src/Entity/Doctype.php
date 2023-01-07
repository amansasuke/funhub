<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Doctype
 *
 * @ORM\Table(name="doctype")
 * @ORM\Entity
 */
class Doctype
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="productdoctype_id", type="integer", nullable=true)
     */
    private $productdoctypeId;

    /**
     * @ORM\OneToMany(targetEntity=Documentsforproduct::class, mappedBy="docinfo")
     */
    private $documentsforproducts;

    /**
     * @ORM\ManyToMany(targetEntity=Docforpro::class, mappedBy="newdocinfo")
     */
    private $docforpros;

    public function __construct()
    {
        $this->documentsforproducts = new ArrayCollection();
        $this->docforpros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProductdoctypeId(): ?int
    {
        return $this->productdoctypeId;
    }

    public function setProductdoctypeId(?int $productdoctypeId): self
    {
        $this->productdoctypeId = $productdoctypeId;

        return $this;
    }

    /**
     * @return Collection<int, Documentsforproduct>
     */
    public function getDocumentsforproducts(): Collection
    {
        return $this->documentsforproducts;
    }

    public function addDocumentsforproduct(Documentsforproduct $documentsforproduct): self
    {
        if (!$this->documentsforproducts->contains($documentsforproduct)) {
            $this->documentsforproducts[] = $documentsforproduct;
            $documentsforproduct->setDocinfo($this);
        }

        return $this;
    }

    public function removeDocumentsforproduct(Documentsforproduct $documentsforproduct): self
    {
        if ($this->documentsforproducts->removeElement($documentsforproduct)) {
            // set the owning side to null (unless already changed)
            if ($documentsforproduct->getDocinfo() === $this) {
                $documentsforproduct->setDocinfo(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection<int, Docforpro>
     */
    public function getDocforpros(): Collection
    {
        return $this->docforpros;
    }

    public function addDocforpro(Docforpro $docforpro): self
    {
        if (!$this->docforpros->contains($docforpro)) {
            $this->docforpros[] = $docforpro;
            $docforpro->addNewdocinfo($this);
        }

        return $this;
    }

    public function removeDocforpro(Docforpro $docforpro): self
    {
        if ($this->docforpros->removeElement($docforpro)) {
            $docforpro->removeNewdocinfo($this);
        }

        return $this;
    }


}
