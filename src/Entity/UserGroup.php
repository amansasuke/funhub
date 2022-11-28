<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 */
class UserGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=AssignGroup::class, inversedBy="userGroups")
     */
    private $assignG;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userGroups")
     */
    private $assignU;

    public function __construct()
    {
        $this->assignG = new ArrayCollection();
        $this->assignU = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, AssignGroup>
     */
    public function getAssignG(): Collection
    {
        return $this->assignG;
    }

    public function addAssignG(AssignGroup $assignG): self
    {
        if (!$this->assignG->contains($assignG)) {
            $this->assignG[] = $assignG;
        }

        return $this;
    }

    public function removeAssignG(AssignGroup $assignG): self
    {
        $this->assignG->removeElement($assignG);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssignU(): Collection
    {
        return $this->assignU;
    }

    public function addAssignU(User $assignU): self
    {
        if (!$this->assignU->contains($assignU)) {
            $this->assignU[] = $assignU;
        }

        return $this;
    }

    public function removeAssignU(User $assignU): self
    {
        $this->assignU->removeElement($assignU);

        return $this;
    }
}
