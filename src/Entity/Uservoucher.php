<?php

namespace App\Entity;

use App\Repository\UservoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UservoucherRepository::class)
 */
class Uservoucher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Voucher::class, inversedBy="User")
     */
    private $Voucher;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="uservouchers")
     */
    private $Users;

    public function __construct()
    {
        $this->Voucher = new ArrayCollection();
        $this->Users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Voucher>
     */
    public function getVoucher(): Collection
    {
        return $this->Voucher;
    }

    public function addVoucher(Voucher $voucher): self
    {
        if (!$this->Voucher->contains($voucher)) {
            $this->Voucher[] = $voucher;
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): self
    {
        $this->Voucher->removeElement($voucher);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->Users->removeElement($user);

        return $this;
    }
}
