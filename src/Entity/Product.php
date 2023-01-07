<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="IDX_D34A04ADED5CA9E6", columns={"service_id"})})
 * @ORM\Entity
 * @Vich\Uploadable()
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="howdone", type="text", length=0, nullable=false)
     */
    private $howdone;

    /**
     * @var string
     *
     * @ORM\Column(name="inclusions", type="text", length=0, nullable=false)
     */
    private $inclusions;

    /**
     * @var string
     *
     * @ORM\Column(name="exclusions", type="text", length=0, nullable=false)
     */
    private $exclusions;

    /**
     * @var string
     *
     * @ORM\Column(name="bonus", type="text", length=0, nullable=false)
     */
    private $bonus;

    /**
     * @var string
     *
     * @ORM\Column(name="estimared", type="text", length=0, nullable=false)
     */
    private $estimared;

    /**
     * @var string
     *
     * @ORM\Column(name="deliverables", type="text", length=0, nullable=false)
     */
    private $deliverables;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="regularprice", type="float", precision=10, scale=0, nullable=false)
     */
    private $regularprice;

    /**
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     * })
     */
    private $service;

    /**
     * @ORM\OneToMany(targetEntity=Documentsforproduct::class, mappedBy="productinfo")
     */
    private $documentinfo;

    /**
     * @ORM\OneToMany(targetEntity=Assigndoc::class, mappedBy="product")
     */
    private $assigndocs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $proimage;

    /**
     * @Vich\UploadableField(mapping="products", fileNameProperty="proimage")
     */
    private $thumbnailFile;

    /**
     * @ORM\OneToMany(targetEntity=Docforpro::class, mappedBy="proinfo")
     */
    private $doctinfo;

    /**
     * @return mixed
     */
    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    /**
     * @param mixed $thumbnailFile
     * @throws \Exception
     */
    public function setThumbnailFile($thumbnailFile): void
    {
        $this->thumbnailFile = $thumbnailFile;
    }

    public function __construct()
    {
        $this->documentinfo = new ArrayCollection();
        $this->assigndocs = new ArrayCollection();
        $this->doctinfo = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHowdone(): ?string
    {
        return $this->howdone;
    }

    public function setHowdone(string $howdone): self
    {
        $this->howdone = $howdone;

        return $this;
    }

    public function getInclusions(): ?string
    {
        return $this->inclusions;
    }

    public function setInclusions(string $inclusions): self
    {
        $this->inclusions = $inclusions;

        return $this;
    }

    public function getExclusions(): ?string
    {
        return $this->exclusions;
    }

    public function setExclusions(string $exclusions): self
    {
        $this->exclusions = $exclusions;

        return $this;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function setBonus(string $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getEstimared(): ?string
    {
        return $this->estimared;
    }

    public function setEstimared(string $estimared): self
    {
        $this->estimared = $estimared;

        return $this;
    }

    public function getDeliverables(): ?string
    {
        return $this->deliverables;
    }

    public function setDeliverables(string $deliverables): self
    {
        $this->deliverables = $deliverables;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRegularprice(): ?float
    {
        return $this->regularprice;
    }

    public function setRegularprice(float $regularprice): self
    {
        $this->regularprice = $regularprice;

        return $this;
    }

    public function getService(): ?Services
    {
        return $this->service;
    }

    public function setService(?Services $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, Documentsforproduct>
     */
    public function getDocumentinfo(): Collection
    {
        return $this->documentinfo;
    }

    public function addDocumentinfo(Documentsforproduct $documentinfo): self
    {
        if (!$this->documentinfo->contains($documentinfo)) {
            $this->documentinfo[] = $documentinfo;
            $documentinfo->setProductinfo($this);
        }

        return $this;
    }

    public function removeDocumentinfo(Documentsforproduct $documentinfo): self
    {
        if ($this->documentinfo->removeElement($documentinfo)) {
            // set the owning side to null (unless already changed)
            if ($documentinfo->getProductinfo() === $this) {
                $documentinfo->setProductinfo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Assigndoc>
     */
    public function getAssigndocs(): Collection
    {
        return $this->assigndocs;
    }

    public function addAssigndoc(Assigndoc $assigndoc): self
    {
        if (!$this->assigndocs->contains($assigndoc)) {
            $this->assigndocs[] = $assigndoc;
            $assigndoc->setProduct($this);
        }

        return $this;
    }

    public function removeAssigndoc(Assigndoc $assigndoc): self
    {
        if ($this->assigndocs->removeElement($assigndoc)) {
            // set the owning side to null (unless already changed)
            if ($assigndoc->getProduct() === $this) {
                $assigndoc->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }


     /**
     * @return mixed
     */
    public function getProimage()
    {
        return $this->proimage;
    }

    /**
     * @param mixed $proimage
     */
    public function setProimage($proimage): void
    {
        $this->proimage = $proimage;

        //return $this;
    }

    /**
     * @return Collection<int, Docforpro>
     */
    public function getDoctinfo(): Collection
    {
        return $this->doctinfo;
    }

    public function addDoctinfo(Docforpro $doctinfo): self
    {
        if (!$this->doctinfo->contains($doctinfo)) {
            $this->doctinfo[] = $doctinfo;
            $doctinfo->setProinfo($this);
        }

        return $this;
    }

    public function removeDoctinfo(Docforpro $doctinfo): self
    {
        if ($this->doctinfo->removeElement($doctinfo)) {
            // set the owning side to null (unless already changed)
            if ($doctinfo->getProinfo() === $this) {
                $doctinfo->setProinfo(null);
            }
        }

        return $this;
    }


}
