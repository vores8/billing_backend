<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\UserBillingItemRepository;
use App\Common\Tarificator;


#[ORM\Entity(repositoryClass: UserBillingItemRepository::class)]
class UserBillingItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBillingItem')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBillingObject $userBillingObject = null;

    #[ORM\ManyToOne(inversedBy: 'userBillingItem')]
    #[ORM\JoinColumn(nullable: false, name: 'reference', referencedColumnName: 'uid')]
    private ?BillingItemReference $billingItemReference = null;

    /**
     * @var Collection<int, Collector>
     */
    #[ORM\OneToMany(targetEntity: Collector::class, mappedBy: 'userBillingItem', orphanRemoval: true, cascade:['persist'])]
    private Collection $collectors;

    #[ORM\Column]
    private ?int $factor = 1;

    public function __construct(BillingItemReference $item, int $factor) {
        $this->billingItemReference = $item;
        $this->factor = $factor;
        $this->collectors = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference() : ?BillingItemReference
    {
        return $this->billingItemReference;
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

    public function getAmountDue(int $startDate, int $endDate)
    {
        $amount = 0;
        foreach ($this->collectors as $collector) {
            $data = $collector->getDataRange($startDate, $endDate);
            $newAmount = Tarificator::apply($data, $collector->getTariff());
            $amount = $amount + $newAmount;
        }
        return $amount;
    }

    public function getCollectorByUID(string $uid): ?Collector {
        return $this->collectors->filter( function ($entity) use ($uid) {
            return $entity->getUid() == $uid;
        })->first();
    }

    public function getFactor(): ?int
    {
        return $this->factor;
    }

    public function setFactor(int $factor): static
    {
        $this->factor = $factor;

        return $this;
    }

}
