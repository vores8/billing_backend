<?php

namespace App\Entity;

use App\Repository\TariffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffRepository::class)]
class UserTariff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Collector>
     */
    #[ORM\OneToMany(targetEntity: Collector::class, mappedBy: 'userTariff', orphanRemoval: true)]
    private Collection $collector;

    #[ORM\ManyToOne(inversedBy: 'tariffs')]
    #[ORM\JoinColumn(nullable: false, name: 'reference', referencedColumnName: 'uid')]
    private ?TariffReference $tariffReference = null;

    #[ORM\Column]
    private array $params = [];

    public function __construct(TariffReference $reference)
    {
        $this->tariffReference = $reference;
        $this->setParams($reference->getParams());
        $this->collector = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Collector>
     */
    public function getCollector(): Collection
    {
        return $this->collector;
    }

    public function addCollector(Collector $collector): static
    {
        if (!$this->collector->contains($collector)) {
            $this->collector->add($collector);
            $collector->setTariff($this);
        }

        return $this;
    }

    public function removeCollector(Collector $collector): static
    {
        if ($this->collector->removeElement($collector)) {
            // set the owning side to null (unless already changed)
            if ($collector->getTariff() === $this) {
                $collector->setTariff(null);
            }
        }

        return $this;
    }

    public function getReference(): ?TariffReference
    {
        return $this->tariffReference;
    }

    public function setReference(?TariffReference $reference): static
    {
        $this->tariffReference = $reference;

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
    public function setParam(string $paramName, float $paramValue): static
    {
        $this->params[$paramName] = $paramValue;

        return $this;
    }

    public function apply
}
