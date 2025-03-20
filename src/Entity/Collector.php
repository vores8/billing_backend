<?php

namespace App\Entity;

use App\Repository\CollectorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectorRepository::class)]
class Collector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    #[ORM\Column]
    private ?int $timestamp = null;

    #[ORM\ManyToOne(inversedBy: 'collectors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBillingData $userBillingItem = null;

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

    public function getUserBillingItem(): ?UserBillingData
    {
        return $this->userBillingItem;
    }

    public function setUserBillingItem(?UserBillingData $userBillingItem): static
    {
        $this->userBillingItem = $userBillingItem;

        return $this;
    }
}
