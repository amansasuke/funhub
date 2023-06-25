<?php

namespace App\Entity;

use App\Repository\AssignGroupUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssignGroupUserRepository::class)
 */
class AssignGroupUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=AssignGroup::class, inversedBy="assignGroupUsers")
     */
    private $assignGroup;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="assignGroupUsers")
     */
    private $assignUser;

    public function __construct()
    {
        $this->assignGroup = new ArrayCollection();
        $this->assignUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, AssignGroup>
     */
    public function getAssignGroup(): Collection
    {
        return $this->assignGroup;
    }

    public function addAssignGroup(AssignGroup $assignGroup): self
    {
        if (!$this->assignGroup->contains($assignGroup)) {
            $this->assignGroup[] = $assignGroup;
        }

        return $this;
    }

    public function removeAssignGroup(AssignGroup $assignGroup): self
    {
        $this->assignGroup->removeElement($assignGroup);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssignUser(): Collection
    {
        return $this->assignUser;
    }

    public function addAssignUser(User $assignUser): self
    {
        if (!$this->assignUser->contains($assignUser)) {
            $this->assignUser[] = $assignUser;
        }

        return $this;
    }

    public function removeAssignUser(User $assignUser): self
    {
        $this->assignUser->removeElement($assignUser);

        return $this;
    }
}
