<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"phone"})
 * @ApiFilter(SearchFilter::class, properties={"country": "exact", "isActive": "exact"})
 * 
 */
class User implements UserInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({
     *  "user.read",
     *  "user.write"
     * 
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({
     * "user.read", 
     * "user.write",
     * "block.update",
     * "block.read"
     * })
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string The hashed password
     * @Groups({
     *  "user.write"
     * }) 
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "user.write",
     *  "user.read"
     * })
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "user.write",
     *  "user.read"
     * })
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "user.read",
     *  "user.write"
     * })
     * @Assert\NotBlank()
     */
    private $fname;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "user.write",
     *  "user.read"
     * })
     * @Assert\NotBlank()
     */
    private $lname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "user.write",
     *  "user.read"
     * })
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     * @Groups({
     *  "user.write",
     *  "user.read",
     *   "block.update",
     *   "block.read"
     * })
     * @Assert\Type("bool")
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "user.write",
     *  "user.read"
     * })
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "user.write",
     *  "user.read"
     * })
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="supervisorUsers")
     * @Groups({
     *  "user.write"
     * })
     */
    private $supervisor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="supervisor")
     * @Groups({
     *  "user.write"
     * })
     */
    private $supervisorUsers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles", inversedBy="users")
     * @Groups({
     *  "user.read",
     *  "user.write"
     * })
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartnerAccount", mappedBy="owner")
     */
    private $partnerAccounts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({
     *  "user.read",
     *  "user.write"
     * })
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({
     *  "user.read",
     *  "user.write"
     * })
     */
    private $rc;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deposit", mappedBy="creator")
     */
    private $creator_deposits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartnerAccount", mappedBy="creator")
     */
    private $creatorAccounts;

    
    public function __construct()
    {
        $this->supervisor = new ArrayCollection();
        $this->supervisorUsers = new ArrayCollection();
        $this->owner_agencies = new ArrayCollection();
        $this->admin_agencies = new ArrayCollection();
        $this->partnerAccounts = new ArrayCollection();
        $this->creator_deposits = new ArrayCollection();
        $this->creatorAccounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): Array
    {
       return [$this->userRoles->getRoleName()];

    }

    

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSupervisor(): Collection
    {
        return $this->supervisor;
    }

    public function addSupervisor(self $supervisor): self
    {
        if (!$this->supervisor->contains($supervisor)) {
            $this->supervisor[] = $supervisor;
        }

        return $this;
    }

    public function removeSupervisor(self $supervisor): self
    {
        if ($this->supervisor->contains($supervisor)) {
            $this->supervisor->removeElement($supervisor);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSupervisorUsers(): Collection
    {
        return $this->supervisorUsers;
    }

    public function addSupervisorUser(self $supervisorUser): self
    {
        if (!$this->supervisorUsers->contains($supervisorUser)) {
            $this->supervisorUsers[] = $supervisorUser;
            $supervisorUser->addSupervisor($this);
        }

        return $this;
    }

    public function removeSupervisorUser(self $supervisorUser): self
    {
        if ($this->supervisorUsers->contains($supervisorUser)) {
            $this->supervisorUsers->removeElement($supervisorUser);
            $supervisorUser->removeSupervisor($this);
        }

        return $this;
    }

    public function getUserRoles(): ?Roles
    {
        return $this->userRoles;
    }

    public function setUserRoles(?Roles $userRoles): self
    {
        $this->userRoles = $userRoles;

        return $this;
    }

   
    public function __toString()
    {
        return $this->fname;
    }

    /**
     * @return Collection|PartnerAccount[]
     */
    public function getPartnerAccounts(): Collection
    {
        return $this->partnerAccounts;
    }

    public function addPartnerAccount(PartnerAccount $partnerAccount): self
    {
        if (!$this->partnerAccounts->contains($partnerAccount)) {
            $this->partnerAccounts[] = $partnerAccount;
            $partnerAccount->setOwner($this);
        }

        return $this;
    }

    public function removePartnerAccount(PartnerAccount $partnerAccount): self
    {
        if ($this->partnerAccounts->contains($partnerAccount)) {
            $this->partnerAccounts->removeElement($partnerAccount);
            // set the owning side to null (unless already changed)
            if ($partnerAccount->getOwner() === $this) {
                $partnerAccount->setOwner(null);
            }
        }

        return $this;
    }

    public function getNinea(): ?int
    {
        return $this->ninea;
    }

    public function setNinea(int $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(string $rc): self
    {
        $this->rc = $rc;

        return $this;
    }

    /**
     * @return Collection|Deposit[]
     */
    public function getCreatorDeposits(): Collection
    {
        return $this->creator_deposits;
    }

    public function addCreatorDeposit(Deposit $creatorDeposit): self
    {
        if (!$this->creator_deposits->contains($creatorDeposit)) {
            $this->creator_deposits[] = $creatorDeposit;
            $creatorDeposit->setCreator($this);
        }

        return $this;
    }

    public function removeCreatorDeposit(Deposit $creatorDeposit): self
    {
        if ($this->creator_deposits->contains($creatorDeposit)) {
            $this->creator_deposits->removeElement($creatorDeposit);
            // set the owning side to null (unless already changed)
            if ($creatorDeposit->getCreator() === $this) {
                $creatorDeposit->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PartnerAccount[]
     */
    public function getCreatorAccounts(): Collection
    {
        return $this->creatorAccounts;
    }

    public function addCreatorAccount(PartnerAccount $creatorAccount): self
    {
        if (!$this->creatorAccounts->contains($creatorAccount)) {
            $this->creatorAccounts[] = $creatorAccount;
            $creatorAccount->setCreator($this);
        }

        return $this;
    }

    public function removeCreatorAccount(PartnerAccount $creatorAccount): self
    {
        if ($this->creatorAccounts->contains($creatorAccount)) {
            $this->creatorAccounts->removeElement($creatorAccount);
            // set the owning side to null (unless already changed)
            if ($creatorAccount->getCreator() === $this) {
                $creatorAccount->setCreator(null);
            }
        }

        return $this;
    }

    
   
 
}
