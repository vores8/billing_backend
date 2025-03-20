<?php

namespace App\Entity;

use App\Repository\UserBillingObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\BillingRepositoryItem;
use App\Entity\BillingRepositoryData;

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
    // #[ORM\ManyToOne(targetEntity: 'App\Entity\BillingRepositoryItem', inversedBy: 'userBillingObjects')]
    // #[ORM\JoinColumn(name: 'repository_billing_item', referencedColumnName: 'uid')]
    // private ?object $repositoryBillingItem = null;

    /**
     * @var Collection<int, UserBillingData>
     */
    #[ORM\OneToMany(targetEntity: UserBillingData::class, mappedBy: 'userBillingObject', orphanRemoval: true, cascade: ['persist'])]
    private Collection $userBillingData;

    public function __construct(BillingRepositoryItem $item) {
        $this->repositoryBillingItem = $item;
        $this->userBillingData = new ArrayCollection();
        $data = new UserBillingData($item);
        $this->addUserBillingData($data);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getRepositoryBillingItem(): ?BillingRepositoryItem
    // {
    //     return $this->repositoryBillingItem;
    // }

    // public function setRepositoryBillingItem(BillingRepositoryItem $repositoryBillingItem): static
    // {
    //     $this->repositoryBillingItem = $repositoryBillingItem;

    //     return $this;
    // }

    /**
     * @return Collection<int, UserBillingData>
     */
    public function getUserBillingData(): Collection
    {
        return $this->userBillingData;
    }

    public function addUserBillingData(UserBillingData $userBillingData): static
    {
        if (!$this->userBillingData->contains($userBillingData)) {
            $this->userBillingData->add($userBillingData);
            $userBillingData->setUserBillingObject($this);
        }

        return $this;
    }

    public function removeUserBillingData(UserBillingData $userBillingData): static
    {
        if ($this->userBillingData->removeElement($userBillingData)) {
            // set the owning side to null (unless already changed)
            if ($userBillingData->getUserBillingObject() === $this) {
                $userBillingData->setUserBillingObject(null);
            }
        }

        return $this;
    }
}
