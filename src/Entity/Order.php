<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\ManyToMany(targetEntity="Product")
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity=Orderdoc::class, mappedBy="orderid")
     */
    private $orderdocs;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $docstatus;

    /**
     * @ORM\OneToMany(targetEntity=DocForClient::class, mappedBy="Ordername")
     */
    private $docForClients;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startdate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $enddate;

    

    public function __construct()
    {
        $this->products = new ArrayCollection;
        $this->orderdocs = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->docForClients = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return Collection<int, Orderdoc>
     */
    public function getOrderdocs(): Collection
    {
        return $this->orderdocs;
    }

    public function addOrderdoc(Orderdoc $orderdoc): self
    {
        if (!$this->orderdocs->contains($orderdoc)) {
            $this->orderdocs[] = $orderdoc;
            $orderdoc->addOrderid($this);
        }

        return $this;
    }

    public function removeOrderdoc(Orderdoc $orderdoc): self
    {
        if ($this->orderdocs->removeElement($orderdoc)) {
            $orderdoc->removeOrderid($this);
        }

        return $this;
    }

    public function __toString() {
        // return $this->orderdocs;
        // return $this->products;
        // return $this->user; 
        return $this->name;  
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function isDocstatus(): ?bool
    {
        return $this->docstatus;
    }

    public function setDocstatus(bool $docstatus): self
    {
        $this->docstatus = $docstatus;

        return $this;
    }

    /**
     * @return Collection<int, DocForClient>
     */
    public function getDocForClients(): Collection
    {
        return $this->docForClients;
    }

    public function addDocForClient(DocForClient $docForClient): self
    {
        if (!$this->docForClients->contains($docForClient)) {
            $this->docForClients[] = $docForClient;
            $docForClient->setOrdername($this);
        }

        return $this;
    }

    public function removeDocForClient(DocForClient $docForClient): self
    {
        if ($this->docForClients->removeElement($docForClient)) {
            // set the owning side to null (unless already changed)
            if ($docForClient->getOrdername() === $this) {
                $docForClient->setOrdername(null);
            }
        }

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(?\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }


  

   
}
