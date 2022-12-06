<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Services
 *
 * @ORM\Table(name="services", indexes={@ORM\Index(name="IDX_7332E16912469DE2", columns={"category_id"}), @ORM\Index(name="IDX_7332E16987CCB12E", columns={"categoryname_id"})})
 * @ORM\Entity
 * @Vich\Uploadable()
 */
class Services
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
     * @ORM\Column(name="servicesname", type="string", length=255, nullable=false)
     */
    private $servicesname;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=100, nullable=false)
     */
    private $thumbnail;

     /**
     * @Vich\UploadableField(mapping="thumbnails", fileNameProperty="thumbnail")
     */
    private $thumbnailFile;

    

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

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoryname_id", referencedColumnName="id")
     * })
     */
    private $categoryname;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServicesname(): ?string
    {
        return $this->servicesname;
    }

    public function setServicesname(string $servicesname): self
    {
        $this->servicesname = $servicesname;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategoryname(): ?Category
    {
        return $this->categoryname;
    }

    public function setCategoryname(?Category $categoryname): self
    {
        $this->categoryname = $categoryname;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    // public function __toString() {
    //     return $this->category;
    // }

    public function __toString() {
        return $this->servicesname;
    }


}
