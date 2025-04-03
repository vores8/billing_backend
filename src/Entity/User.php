<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, UserBillingObject>
     */
    #[ORM\OneToMany(targetEntity: UserBillingObject::class, mappedBy: 'user')]
    private Collection $billigObject;

    public function __construct()
    {
        $this->billigObject = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, UserBillingObject>
     */
    public function getBilligObject(): Collection
    {
        return $this->billigObject;
    }

    public function addBilligObject(UserBillingObject $billigObject): static
    {
        if (!$this->billigObject->contains($billigObject)) {
            $this->billigObject->add($billigObject);
            $billigObject->setUser($this);
        }

        return $this;
    }

    public function removeBilligObject(UserBillingObject $billigObject): static
    {
        if ($this->billigObject->removeElement($billigObject)) {
            // set the owning side to null (unless already changed)
            if ($billigObject->getUser() === $this) {
                $billigObject->setUser(null);
            }
        }

        return $this;
    }
}
