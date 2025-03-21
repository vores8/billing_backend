<?php

namespace App\Entity;

use App\Repository\UserBillingDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBillingDataRepository::class)]
class Userbilingitem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBilingItem')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBillingObject $userBillingObject = null;

    #[ORM\ManyToOne(inversedBy: 'userBilingItem')]
    #[ORM\JoinColumn(nullable: false, name: 'reference', referencedColumnName: 'uid')]
    private ?BillingItemReference $billingItemReference = null;

    /**
     * @var Collection<int, Collector>
     */
    #[ORM\OneToMany(targetEntity: Collector::class, mappedBy: 'userBillingItem', orphanRemoval: true)]
    private Collection $collectors;

    public function __construct(BillingItemReference $item) {
        $this->billingItemReference = $item;
        $this->collectors = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserBillingObject(): ?UserBillingObject
    {
        return $this->userBillingObject;
    }

    public function setUserBillingObject(?UserBillingObject $userBillingObject): static
    {
        $this->userBillingObject = $userBillingObject;

        return $this;
    }

    public function getBillingItemReference(): ?BillingItemReference
    {
        return $this->billingItemReference;
    }

    public function setBillingItemReference(?BillingItemReference $billingItemReference): static
    {
        $this->billingItemReference = $billingItemReference;

        return $this;
    }

    /**
     * @return Collection<int, Collector>
     */
    public function getCollectors(): Collection
    {
        return $this->collectors;
    }

    public function addCollector(Collector $collector): static
    {
        if (!$this->collectors->contains($collector)) {
            $this->collectors->add($collector);
            $collector->setUserBillingItem($this);
        }

        return $this;
    }

    public function removeCollector(Collector $collector): static
    {
        if ($this->collectors->removeElement($collector)) {
            // set the owning side to null (unless already changed)
            if ($collector->getUserBillingItem() === $this) {
                $collector->setUserBillingItem(null);
            }
        }

        return $this;
    }
}
