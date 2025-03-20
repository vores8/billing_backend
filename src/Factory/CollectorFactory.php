<?php

namespace App\Factory;

use Doctrine\Common\Collections\ArrayCollection;

use App\Common\BillingItemUID;
use App\Common\CollectorUID;
use App\Common\TariffUID;

use App\Entity\Collector;
use App\Entity\Tariff;

class CollectorFactory {

    static public function createCollectors(string $uid): ?Collection {
        if ($uid == BillingItemUID::ColocationSingleRack) {
            $collectors = new ArrayCollection();

            $collector  = new Collector();
            $collector->setUid(CollectorUID::RackSpace);
            $collector->setTariff(TariffUID::RackSpaceSingleRack);
            $collectors->add($collector);

            $collector  = new Collector();
            $collector->setUid(CollectorUID::PowerUsage);
            $collector->setTariff(TariffUID::PowerUsage);
            $collectors->add($collector);
        }

        return $collectors;
    }

}