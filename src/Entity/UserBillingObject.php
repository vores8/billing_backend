<?php

namespace App\Entity;

use App\Repository\UserBillingObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\BillingItemReference;
use App\Entity\UserBillingItem;
// use App\Entity\BillingRepositoryData;

#[ORM\Entity(repositoryClass: UserBillingObjectRepository::class)]
class UserBillingObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(type: 'string', length: 36)]
    // private string $repositoryBillingItemUID;

    // Define the many-to-one relationship without referencing the Rep class
    // #[ORM\ManyToOne(targetEntity: 'App\Entity\BillingItemReference', inversedBy: 'userBillingObjects')]
    // #[ORM\JoinColumn(name: 'repository_billing_item', referencedColumnName: 'uid')]
    // private ?object $repositoryBillingItem = null;

    /**
     * @var Collection<int, UserBilingItem>
     */
    #[ORM\OneToOne(targetEntity: UserBillingItem::class, mappedBy: 'userBillingObject', orphanRemoval: true, cascade: ['persist'])]
    private UserBillingItem $billingItem;

    #[ORM\ManyToOne(inversedBy: 'billigObject')]
    private ?User $user = null;

    public function __construct(User $user, BillingItemReference $item, int $factor = 1) {
        $this->setUser($user);
        $this->setUserBillingItem(new UserBillingItem($item, $factor));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getRepositoryBillingItem(): ?BillingItemReference
    // {
    //     return $this->repositoryBillingItem;
    // }

    // public function setRepositoryBillingItem(BillingItemReference $repositoryBillingItem): static
    // {
    //     $this->repositoryBillingItem = $repositoryBillingItem;

    //     return $this;
    // }

    /**
     * @return UserBilingItem
     */
    public function getUserBillingItem(): UserBillingItem
    {
        return $this->billingItem;
    }

    public function setUserBillingItem(UserBillingItem $item): static
    {
        $this->billingItem = $item;
        $item->setUserBillingObject($this);
        return $this;
    }

    public function getAmountDue(int $startDate, int $endDate)
    {
        $amount = 0;
        $amount = $amount + $this->billingItem->getAmountDue($startDate, $endDate);
        return $amount;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
