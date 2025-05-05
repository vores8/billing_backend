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

    static function calculateAverage(Collection $data) : float
    {

        if (count($data) <= 0) {
            return 0;
        }

        $sortedData = $data->toArray();
        
        usort($sortedData, function ($a, $b) {
            return $a->getTimestamp() <=> $b->getTimestamp();
        });

        $totalAmount = 0;
        $totalDuration = 0;
        
        for ($i = 1; $i < count($data); $i++) {
            $currentTimestamp = $sortedData[$i - 1]->getTimestamp();
            $nextTimestamp = $sortedData[$i]->getTimestamp();       
            $duration = $nextTimestamp - $currentTimestamp;
            $amount = 0.5 * ($sortedData[$i]->getAmount() + $sortedData[$i - 1]->getAmount());
            $totalAmount += $amount * $duration;
        }

        $minTimestamp = min($data->map(fn($item) => $item->getTimestamp())->toArray());
        $maxTimestamp = max($data->map(fn($item) => $item->getTimestamp())->toArray());             
        $totalDuration = $maxTimestamp - $minTimestamp;        

        $averageAmount = $totalAmount / $totalDuration;

        // return $totalAmount;
        return $averageAmount;

    }

    static function calculateCumulative(Collection $data) : float
    {

        if (count($data) <= 0) {
            return 0;
        }

        $totalAmount = 0;
        
        for ($i = 0; $i < count($data); $i++) {
            $totalAmount += $data[$i]->getAmount();
        }

        return $totalAmount;
    }

    public static function apply(Collection $collectorData, UserTariff $tariff, int $factor): float 
    {

        if ($tariff->getreference()->getUid() == TariffUID::Flat) {
            return $tariff->getParams()['rate'];

        } elseif ($tariff->getreference()->getUid() == TariffUID::Average) {
            $average = Tarificator::calculateAverage($collectorData);
            return $tariff->getParams()['rate'] * $average;

        } elseif ($tariff->getreference()->getUid() == TariffUID::AverageRateAbove) {
            $average = Tarificator::calculateAverage($collectorData);
            $limit = $tariff->getParams()['limit'];

            $factoredLimit = $limit * $factor;

            if ($average <= $factoredLimit)
            {
                return $tariff->getParams()['rate'] * $average;
            }
            $belowLimit = $average;
            $aboveLimit = $average - $factoredLimit;

            return $tariff->getParams()['rate'] * $belowLimit + $tariff->getParams()['above'] * $aboveLimit;

        }  elseif ($tariff->getreference()->getUid() == TariffUID::AverageFactorAbove) {
            $average = Tarificator::calculateAverage($collectorData);
            $limit = $tariff->getParams()['limit'];

            $factoredLimit = $limit * $factor;

            if ($average <= $factoredLimit)
            {
                return $tariff->getParams()['rate'] * $average;
            }
            $belowLimit = $average;
            $aboveLimit = $average - $factoredLimit;

            return $tariff->getParams()['rate'] * $belowLimit + $tariff->getParams()['rate'] * $tariff->getParams()['factor'] * $aboveLimit;

        } elseif ($tariff->getreference()->getUid() == TariffUID::Cumulative) {
            $amount = Tarificator::calculateCumulative($collectorData);
            return $tariff->getParams()['rate'] * $amount;

        } elseif ($tariff->getreference()->getUid() == TariffUID::CumulativeRateAbove) {
            $amount = Tarificator::calculateCumulative($collectorData);
            $limit = $tariff->getParams()['limit'];

            $factoredLimit = $limit * $factor;

            if ($amount <= $factoredLimit)
            {
                return $tariff->getParams()['rate'] * $amount;
            }
            $belowLimit = $amount;
            $aboveLimit = $amount - $factoredLimit;

            return $tariff->getParams()['rate'] * $belowLimit + $tariff->getParams()['rate'] * $aboveLimit;

        }  elseif ($tariff->getreference()->getUid() == TariffUID::CumulativeFactorAbove) {
            $amount = Tarificator::calculateCumulative($collectorData);
            $limit = $tariff->getParams()['limit'];

            $factoredLimit = $limit * $factor;

            if ($amount <= $factoredLimit)
            {
                return $tariff->getParams()['rate'] * $amount;
            }
            $belowLimit = $amount;
            $aboveLimit = $amount - $factoredLimit;

            return $tariff->getParams()['rate'] * $belowLimit + $tariff->getParams()['rate'] * $tariff->getParams()['factor'] * $aboveLimit;

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
    //         $collector->setUid(CollectorUID::CollectorDynamic);
    //         $tr = $this->manager->getRepository(TariffReference::class)->find(TariffUID::CollectorDynamic);
    //         $collector->setTariff(new UserTariff($tr));
    //         $collectors->add($collector);
    //     }

    //     return $collectors;
    // }

}