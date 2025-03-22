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
    #[ORM\OneToMany(targetEntity: UserBillingItem::class, mappedBy: 'userBillingObject', orphanRemoval: true, cascade: ['persist'])]
    private Collection $billingItems;

    public function __construct(BillingItemReference $item) {
        // $this->repositoryBillingItem = $item;
        $this->billingItems = new ArrayCollection();
        $data = new UserBillingItem($item);
        $this->addUserBillingData($data);
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
     * @return Collection<int, UserBilingItem>
     */
    public function getUserBillingData(): Collection
    {
        return $this->billingItems;
    }

    public function addUserBillingData(UserBillingItem $item): static
    {
        if (!$this->billingItems->contains($item)) {
            $this->billingItems->add($item);
            $item->setUserBillingObject($this);
        }

        return $this;
    }

    public function removeUserBillingData(UserBillingItem $userBillingItem): static
    {
        if ($this->billingItems->removeElement($userBillingItem)) {
            // set the owning side to null (unless already changed)
            if ($userBillingItem->getUserBillingObject() === $this) {
                $userBillingItem->setUserBillingObject(null);
            }
        }

        return $this;
    }
}
