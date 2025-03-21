<?php

namespace App\Entity;

use App\Repository\UserBillingObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\BillingItemReference;
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
    // #[ORM\ManyToOne(targetEntity: 'App\Entity\BillingItemReference', inversedBy: 'userBillingObjects')]
    // #[ORM\JoinColumn(name: 'repository_billing_item', referencedColumnName: 'uid')]
    // private ?object $repositoryBillingItem = null;

    /**
     * @var Collection<int, Userbilingitem>
     */
    #[ORM\OneToMany(targetEntity: Userbilingitem::class, mappedBy: 'userBillingObject', orphanRemoval: true, cascade: ['persist'])]
    private Collection $userbilingitem;

    public function __construct(BillingItemReference $item) {
        $this->repositoryBillingItem = $item;
        $this->userbilingitem = new ArrayCollection();
        $data = new Userbilingitem($item);
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
     * @return Collection<int, Userbilingitem>
     */
    public function getUserBillingData(): Collection
    {
        return $this->userbilingitem;
    }

    public function addUserBillingData(Userbilingitem $userbilingitem): static
    {
        if (!$this->userbilingitem->contains($userbilingitem)) {
            $this->userbilingitem->add($userbilingitem);
            $userbilingitem->setUserBillingObject($this);
        }

        return $this;
    }

    public function removeUserBillingData(Userbilingitem $userbilingitem): static
    {
        if ($this->userbilingitem->removeElement($userbilingitem)) {
            // set the owning side to null (unless already changed)
            if ($userbilingitem->getUserBillingObject() === $this) {
                $userbilingitem->setUserBillingObject(null);
            }
        }

        return $this;
    }
}
