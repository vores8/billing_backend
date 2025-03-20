<?php

namespace App\Entity;

use App\Repository\CollectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectorRepository::class)]
class Collector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'collectors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBillingData $userBillingItem = null;

    /**
     * @var Collection<int, CollectorData>
     */
    #[ORM\OneToMany(targetEntity: CollectorData::class, mappedBy: 'collector', orphanRemoval: true)]
    private Collection $collectorData;

    #[ORM\Column(length: 36)]
    private ?string $uid = null;

    #[ORM\ManyToOne(inversedBy: 'collector')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tariff $tariff = null;

    public function __construct()
    {
        $this->collectorData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserBillingItem(): ?UserBillingData
    {
        return $this->userBillingItem;
    }

    public function setUserBillingItem(?UserBillingData $userBillingItem): static
    {
        $this->userBillingItem = $userBillingItem;

        return $this;
    }

    /**
     * @return Collection<int, CollectorData>
     */
    public function getCollectorData(): Collection
    {
        return $this->collectorData;
    }

    public function addCollectorData(CollectorData $collectorData): static
    {
        if (!$this->collectorData->contains($collectorData)) {
            $this->collectorData->add($collectorData);
            $collectorData->setCollector($this);
        }

        return $this;
    }

    public function removeCollectorData(CollectorData $collectorData): static
    {
        if ($this->collectorData->removeElement($collectorData)) {
            // set the owning side to null (unless already changed)
            if ($collectorData->getCollector() === $this) {
                $collectorData->setCollector(null);
            }
        }

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    public function getTariff(): ?Tariff
    {
        return $this->tariff;
    }

    public function setTariff(?Tariff $tariff): static
    {
        $this->tariff = $tariff;

        return $this;
    }
}
