<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\BillingItemReferenceRepository;

#[ORM\Entity(repositoryClass: BillingItemReferenceRepository::class)]
class BillingItemReference
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
     * @var Collection<int, Userbilingitem>
     */
    #[ORM\OneToMany(targetEntity: Userbilingitem::class, mappedBy: 'billingItemReference', orphanRemoval: true)]
    private Collection $userbilingitem;

    public function __construct($uid) {
        $this->userBillingObjects = new ArrayCollection();
        $this->uid = $uid;
        $this->userbilingitem = new ArrayCollection();
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
            $userbilingitem->setBillingItemReference($this);
        }

        return $this;
    }

    public function removeUserBillingData(Userbilingitem $userbilingitem): static
    {
        if ($this->userbilingitem->removeElement($userbilingitem)) {
            // set the owning side to null (unless already changed)
            if ($userbilingitem->getBillingItemReference() === $this) {
                $userbilingitem->setBillingItemReference(null);
            }
        }

        return $this;
    }


}
