<?php

namespace App\Entity;

use App\Repository\TariffReferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffReferenceRepository::class)]
class TariffReference
{
    #[ORM\Id]
    #[ORM\Column(name: 'uid', length: 36)]
    private ?string $uid = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, UserTariff>
     */
    #[ORM\OneToMany(targetEntity: UserTariff::class, mappedBy: 'reference', orphanRemoval: true)]
    private Collection $tariffs;

    #[ORM\Column]
    private array $params = [];

    public function __construct($uid)
    {
        $this->uid = $uid;
        $this->tariffs = new ArrayCollection();
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

    /**
     * @return Collection<int, UserTariff>
     */
    public function getTariffs(): Collection
    {
        return $this->tariffs;
    }

    public function addTariff(UserTariff $usertariff): static
    {
        if (!$this->tariffs->contains($usertariff)) {
            $this->tariffs->add($usertariff);
            $usertariff->setReference($this);
        }

        return $this;
    }

    public function removeTariff(UserTariff $usertariff): static
    {
        if ($this->tariffs->removeElement($usertariff)) {
            // set the owning side to null (unless already changed)
            if ($usertariff->getReference() === $this) {
                $usertariff->setReference(null);
            }
        }

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }
}
