<?php

namespace App\Entity;

use App\Repository\CollectorDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectorDataRepository::class)]
class CollectorData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    #[ORM\Column]
    private ?int $timestamp = null;

    #[ORM\ManyToOne(inversedBy: 'collectorData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collector $collector = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getCollector(): ?Collector
    {
        return $this->collector;
    }

    public function setCollector(?Collector $collector): static
    {
        $this->collector = $collector;

        return $this;
    }
}
