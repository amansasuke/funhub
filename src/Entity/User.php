<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pan_no;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone_no;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_category;

    /**
     * @ORM\Column(type="integer")
     */
    private $red_id;

    /**
     * @ORM\Column(type="float")
     */
    private $wellet;

    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, inversedBy="users")
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=AssignGroup::class, mappedBy="user")
     */
    private $assignGroups;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, mappedBy="assignU")
     */
    private $userGroups;

    /**
     * @ORM\OneToMany(targetEntity=Vouchercode::class, mappedBy="User")
     */
    private $vouchercodes;

    /**
     * @ORM\ManyToMany(targetEntity=Uservoucher::class, mappedBy="Users")
     */
    private $uservouchers;

    /**
     * @ORM\OneToMany(targetEntity=Club::class, mappedBy="user")
     */
    private $clubs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imgicon;

    /**
     * @ORM\OneToMany(targetEntity=Eventbooking::class, mappedBy="manger")
     */
    private $eventbookings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $GSTno;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;


    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->assignGroups = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->vouchercodes = new ArrayCollection();
        $this->uservouchers = new ArrayCollection();
        $this->clubs = new ArrayCollection();
        $this->eventbookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $userRoles = $this->getRole();
        foreach ($userRoles as $userRole) {
            $roles[] = $userRole->getRoleName();
        }
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeUser($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->email;
        return $this->role;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPanNo(): ?string
    {
        return $this->pan_no;
    }

    public function setPanNo(string $pan_no): self
    {
        $this->pan_no = $pan_no;

        return $this;
    }

    public function getPhoneNo(): ?string
    {
        return $this->phone_no;
    }

    public function setPhoneNo(string $phone_no): self
    {
        $this->phone_no = $phone_no;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getUserCategory(): ?string
    {
        return $this->user_category;
    }

    public function setUserCategory(string $user_category): self
    {
        $this->user_category = $user_category;

        return $this;
    }

    public function getRedId(): ?int
    {
        return $this->red_id;
    }

    public function setRedId(int $red_id): self
    {
        $this->red_id = $red_id;

        return $this;
    }

    public function getWellet(): ?float
    {
        return $this->wellet;
    }

    public function setWellet(float $wellet): self
    {
        $this->wellet = $wellet;

        return $this;
    }

    /**
     * @return Collection<int, Roles>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }

    /**
     * @return Collection<int, AssignGroup>
     */
    public function getAssignGroups(): Collection
    {
        return $this->assignGroups;
    }

    public function addAssignGroup(AssignGroup $assignGroup): self
    {
        if (!$this->assignGroups->contains($assignGroup)) {
            $this->assignGroups[] = $assignGroup;
            $assignGroup->addUser($this);
        }

        return $this;
    }

    public function removeAssignGroup(AssignGroup $assignGroup): self
    {
        if ($this->assignGroups->removeElement($assignGroup)) {
            $assignGroup->removeUser($this);
        }

        return $this;
    }


    /**
     * @return Collection<int, UserGroup>
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->addAssignU($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            $userGroup->removeAssignU($this);
        }

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
            $vouchercode->setUser($this);
        }

        return $this;
    }

    public function removeVouchercode(Vouchercode $vouchercode): self
    {
        if ($this->vouchercodes->removeElement($vouchercode)) {
            // set the owning side to null (unless already changed)
            if ($vouchercode->getUser() === $this) {
                $vouchercode->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Uservoucher>
     */
    public function getUservouchers(): Collection
    {
        return $this->uservouchers;
    }

    public function addUservoucher(Uservoucher $uservoucher): self
    {
        if (!$this->uservouchers->contains($uservoucher)) {
            $this->uservouchers[] = $uservoucher;
            $uservoucher->addUser($this);
        }

        return $this;
    }

    public function removeUservoucher(Uservoucher $uservoucher): self
    {
        if ($this->uservouchers->removeElement($uservoucher)) {
            $uservoucher->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs[] = $club;
            $club->setUser($this);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getUser() === $this) {
                $club->setUser(null);
            }
        }

        return $this;
    }

    public function getImgicon(): ?string
    {
        return $this->imgicon;
    }

    public function setImgicon(string $imgicon): self
    {
        $this->imgicon = $imgicon;

        return $this;
    }

    /**
     * @return Collection<int, Eventbooking>
     */
    public function getEventbookings(): Collection
    {
        return $this->eventbookings;
    }

    public function addEventbooking(Eventbooking $eventbooking): self
    {
        if (!$this->eventbookings->contains($eventbooking)) {
            $this->eventbookings[] = $eventbooking;
            $eventbooking->setManger($this);
        }

        return $this;
    }

    public function removeEventbooking(Eventbooking $eventbooking): self
    {
        if ($this->eventbookings->removeElement($eventbooking)) {
            // set the owning side to null (unless already changed)
            if ($eventbooking->getManger() === $this) {
                $eventbooking->setManger(null);
            }
        }

        return $this;
    }

    public function getGSTno(): ?string
    {
        return $this->GSTno;
    }

    public function setGSTno(?string $GSTno): self
    {
        $this->GSTno = $GSTno;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    
}
