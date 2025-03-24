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
use App\Entity\UserTariff;
use App\Entity\UserBillingItem;
use App\Entity\TariffReference;

class CollectorFactory {


    static public function createCollectors(UserBillingItem $item, EntityManagerInterface $manager): ?UserBillingItem {
        if ($item->getReference()->getUid() == BillingItemUID::ColocationSingleRack) {

            $collector  = new Collector();
            $collector->setUid(CollectorUID::RackSpace);

            $tr = $manager->getRepository(TariffReference::class)->find(TariffUID::RackSpaceSingleRack);
            $collector->setTariff(new UserTariff($tr));
            $item->addCollector($collector);

            $collector  = new Collector();
            $collector->setUid(CollectorUID::PowerUsage);
            $tr = $manager->getRepository(TariffReference::class)->find(TariffUID::PowerUsage);
            $collector->setTariff(new UserTariff($tr));
            $item->addCollector($collector);
        }

        return $item;
    }

    // public function getCollectorData(Collector $collector, int $startDate, int $endDate, EntityManagerInterface $manager): array
    // {
    //     $qb = $this->entityManager->createQueryBuilder();

    //     $query = $qb->select('c')
    //         ->from('App\Entity\CollectorData', 'c')
    //         ->where('c.timestamp >= :start')
    //         ->andWhere('c.timestamp <= :end')
    //         ->setParameter('start', $startDate)
    //         ->setParameter('end', $endDate)
    //         ->getQuery();

    //     return $query->getResult();
    // }


}