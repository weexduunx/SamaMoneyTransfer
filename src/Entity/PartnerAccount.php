<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartnerAccountRepository")
 */
class PartnerAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"account.write", "account.read"})
     */
    private $id;

     /**
     * @ORM\Column(type="string")
     * @Groups({"account.write", "account.read"})
     */
    private $accountNumber = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"account.write", "account.read"})
     */
    private $balance = 0;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"account.write", "account.read"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="partnerAccounts")
     * @Groups({"account.write", "account.read"})
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deposit", mappedBy="account", cascade={"persist"})
     * @Groups({"account.write", "account.read"})
     */
    private $deposits;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="creatorAccounts")
     */
    private $creator;

  

    public function __construct()
    {
        
        $this->deposits = new ArrayCollection();
        $this->setCreatedAt(new \DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Deposit[]
     */
    public function getDeposits(): Collection
    {
        return $this->deposits;
    }

    public function addDeposit(Deposit $deposit): self
    {
        if (!$this->deposits->contains($deposit)) {
            $this->deposits[] = $deposit;
            $deposit->setAccount($this);
        }

        return $this;
    }

    public function removeDeposit(Deposit $deposit): self
    {
        if ($this->deposits->contains($deposit)) {
            $this->deposits->removeElement($deposit);
            // set the owning side to null (unless already changed)
            if ($deposit->getAccount() === $this) {
                $deposit->setAccount(null);
            }
        }

        return $this;
    }

    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(int $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function __toString()
    {
        return $this->accountNumber;
    }
}
