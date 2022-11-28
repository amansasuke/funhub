<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 * @Vich\Uploadable()
 */
class Voucher
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
     * @ORM\Column(type="integer")
     */
    private $prices;

    /**
     * @ORM\OneToMany(targetEntity=Vouchercode::class, mappedBy="v")
     */
    private $vouchercodes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Vouchericon;

    /**
     * @ORM\ManyToMany(targetEntity=Uservoucher::class, mappedBy="Voucher")
     */
    private $User;

    public function __construct()
    {
        $this->vouchercodes = new ArrayCollection();
        $this->User = new ArrayCollection();
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

    public function getPrices(): ?int
    {
        return $this->prices;
    }

    public function setPrices(int $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @return Collection<int, Vouchercode>
     */
    public function getVouchercodes(): Collection
    {
        return $this->vouchercodes;
    }

    public function addVouchercode(Vouchercode $vouchercode): self
    {
        if (!$this->vouchercodes->contains($vouchercode)) {
            $this->vouchercodes[] = $vouchercode;
            $vouchercode->setV($this);
        }

        return $this;
    }

    public function removeVouchercode(Vouchercode $vouchercode): self
    {
        if ($this->vouchercodes->removeElement($vouchercode)) {
            // set the owning side to null (unless already changed)
            if ($vouchercode->getV() === $this) {
                $vouchercode->setV(null);
            }
        }

        return $this;
    }

    public function getVouchericon(): ?string
    {
        return $this->Vouchericon;
    }

    public function setVouchericon(string $Vouchericon): self
    {
        $this->Vouchericon = $Vouchericon;

        return $this;
    }
    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection<int, Uservoucher>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(Uservoucher $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
            $user->addVoucher($this);
        }

        return $this;
    }

    public function removeUser(Uservoucher $user): self
    {
        if ($this->User->removeElement($user)) {
            $user->removeVoucher($this);
        }

        return $this;
    }
}
