<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\BillingRepositoryItemRepository;

#[ORM\Entity(repositoryClass: BillingRepositoryItemRepository::class)]
class BillingRepositoryItem
{
    #[ORM\Id]
    // #[ORM\GeneratedValue]
    #[ORM\Column(name: 'uid', length: 36)]
    private ?string $uid = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_root = null;

    #[ORM\OneToMany(targetEntity: 'App\Entity\UserBillingObject', mappedBy: 'repository_item_id')]
    private Collection $userBillingObjects;

    /**
     * @var Collection<int, UserBillingData>
     */
    #[ORM\OneToMany(targetEntity: UserBillingData::class, mappedBy: 'billingRepositoryItem', orphanRemoval: true)]
    private Collection $userBillingData;

    public function __construct($uid) {
        $this->userBillingObjects = new ArrayCollection();
        $this->uid = $uid;
        $this->userBillingData = new ArrayCollection();
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getIsRoot(): ?bool
    {
        return $this->is_root;
    }

    public function setIsRoot(?bool $root): static
    {
        $this->is_root = $root;

        return $this;
    }

    public function getUserBillingObjects(): Collection
    {
        return $this->userBillingObjects;
    }

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
            $userBillingData->setBillingRepositoryItem($this);
        }

        return $this;
    }

    public function removeUserBillingData(UserBillingData $userBillingData): static
    {
        if ($this->userBillingData->removeElement($userBillingData)) {
            // set the owning side to null (unless already changed)
            if ($userBillingData->getBillingRepositoryItem() === $this) {
                $userBillingData->setBillingRepositoryItem(null);
            }
        }

        return $this;
    }


}
