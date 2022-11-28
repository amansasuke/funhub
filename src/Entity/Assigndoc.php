<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Assigndoc
 *
 * @ORM\Table(name="Assigndoc")
 * @ORM\Entity
 */
class Assigndoc
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="assigndocs")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $docname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
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
}
