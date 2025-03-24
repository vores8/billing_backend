<?php

namespace App\Common;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
// use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

use App\Common\BillingItemUID;
use App\Common\CollectorUID;
use App\Common\TariffUID;

use App\Entity\Collector;
use App\Entity\CollectorData;
use App\Entity\UserTariff;
use App\Entity\UserBillingItem;
use App\Entity\TariffReference;

class Tarificator {

    // private EntityManagerInterface $manager;

    // public function __construct(EntityManagerInterface $entityManager)
    // {
    //     $this->manager = $entityManager;
    // }

    public static function apply(Collection $collectorData, UserTariff $tariff): float 
    {

        if ($tariff->getreference()->getUid() == TariffUID::Flat) {
            return $tariff->getParams()['rate'];

        } elseif ($tariff->getreference()->getUid() == TariffUID::PowerUsage) {
            $amount = 0;

            foreach($collectorData as $data) {
                $amount = $amount + $data->getAmount();
            }
            return $tariff->getParams()['rate'] * $amount;

        }
        return 0;

    }

    // public function createCollectors(UserBillingItem $item): ?Collection {
    //     if ($item->getReference()->getUid() == BillingItemUID::ColocationSingleRack) {
    //         $collectors = new ArrayCollection();

    //         $collector  = new Collector();
    //         $collector->setUid(CollectorUID::RackSpace);

    //         $tr = $this->manager->getRepository(TariffReference::class)->find(TariffUID::RackSpaceSingleRack);
    //         $collector->setTariff(new UserTariff($tr));
    //         $collectors->add($collector);

    //         $collector  = new Collector();
    //         $collector->setUid(CollectorUID::PowerUsage);
    //         $tr = $this->manager->getRepository(TariffReference::class)->find(TariffUID::PowerUsage);
    //         $collector->setTariff(new UserTariff($tr));
    //         $collectors->add($collector);
    //     }

    //     return $collectors;
    // }

}