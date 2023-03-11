<?php

namespace App\Entity;

use App\Repository\AffiliateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AffiliateRepository::class)
 */
class Affiliate
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $panno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $holder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $IFSC;

    /**
     * @ORM\Column(type="integer")
     */
    private $userid;

    /**
     * @ORM\OneToMany(targetEntity=Affiliateproduct::class, mappedBy="affiliateid")
     */
    private $affiliateproducts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $upiid;

    public function __construct()
    {
        $this->affiliateproducts = new ArrayCollection();
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

    public function getPhoneno(): ?string
    {
        return $this->phoneno;
    }

    public function setPhoneno(string $phoneno): self
    {
        $this->phoneno = $phoneno;

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

    public function getPanno(): ?string
    {
        return $this->panno;
    }

    public function setPanno(string $panno): self
    {
        $this->panno = $panno;

        return $this;
    }

    public function getAccountname(): ?string
    {
        return $this->accountname;
    }

    public function setAccountname(string $accountname): self
    {
        $this->accountname = $accountname;

        return $this;
    }

    public function getHolder(): ?string
    {
        return $this->holder;
    }

    public function setHolder(string $holder): self
    {
        $this->holder = $holder;

        return $this;
    }

    public function getAccountno(): ?string
    {
        return $this->accountno;
    }

    public function setAccountno(string $accountno): self
    {
        $this->accountno = $accountno;

        return $this;
    }

    public function getIFSC(): ?string
    {
        return $this->IFSC;
    }

    public function setIFSC(string $IFSC): self
    {
        $this->IFSC = $IFSC;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * @return Collection<int, Affiliateproduct>
     */
    public function getAffiliateproducts(): Collection
    {
        return $this->affiliateproducts;
    }

    public function addAffiliateproduct(Affiliateproduct $affiliateproduct): self
    {
        if (!$this->affiliateproducts->contains($affiliateproduct)) {
            $this->affiliateproducts[] = $affiliateproduct;
            $affiliateproduct->setAffiliateid($this);
        }

        return $this;
    }

    public function removeAffiliateproduct(Affiliateproduct $affiliateproduct): self
    {
        if ($this->affiliateproducts->removeElement($affiliateproduct)) {
            // set the owning side to null (unless already changed)
            if ($affiliateproduct->getAffiliateid() === $this) {
                $affiliateproduct->setAffiliateid(null);
            }
        }

        return $this;
    }

    public function getUpiid(): ?string
    {
        return $this->upiid;
    }

    public function setUpiid(?string $upiid): self
    {
        $this->upiid = $upiid;

        return $this;
    }
}
