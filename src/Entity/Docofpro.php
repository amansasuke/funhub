<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Docofpro
 *
 * @ORM\Table(name="docofpro", indexes={@ORM\Index(name="docid", columns={"docid"}), @ORM\Index(name="proid", columns={"proid"})})
 * @ORM\Entity
 */
class Docofpro
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
     * @var \Doctype
     *
     * @ORM\ManyToOne(targetEntity="Doctype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="docid", referencedColumnName="id")
     * })
     */
    private $docid;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proid", referencedColumnName="id")
     * })
     */
    private $proid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocid(): ?Doctype
    {
        return $this->docid;
    }

    public function setDocid(?Doctype $docid): self
    {
        $this->docid = $docid;

        return $this;
    }

    public function getProid(): ?Product
    {
        return $this->proid;
    }

    public function setProid(?Product $proid): self
    {
        $this->proid = $proid;

        return $this;
    }


}
