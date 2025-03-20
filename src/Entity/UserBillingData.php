<?php

namespace App\Entity;

use App\Repository\UserBillingDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBillingDataRepository::class)]
class UserBillingData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBillingData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBillingObject $userBillingObject = null;

    #[ORM\ManyToOne(inversedBy: 'userBillingData')]
    #[ORM\JoinColumn(nullable: false, name: 'repository_billing_item_id', referencedColumnName: 'uid')]
    private ?BillingRepositoryItem $billingRepositoryItem = null;

    public function __construct(BillingRepositoryItem $item) {
        $this->billingRepositoryItem = $item;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserBillingObject(): ?UserBillingObject
    {
        return $this->userBillingObject;
    }

    public function setUserBillingObject(?UserBillingObject $userBillingObject): static
    {
        $this->userBillingObject = $userBillingObject;

        return $this;
    }

    public function getBillingRepositoryItem(): ?BillingRepositoryItem
    {
        return $this->billingRepositoryItem;
    }

    public function setBillingRepositoryItem(?BillingRepositoryItem $billingRepositoryItem): static
    {
        $this->billingRepositoryItem = $billingRepositoryItem;

        return $this;
    }
}
