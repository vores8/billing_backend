<?php

namespace App\Tests\Functional;

// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use App\Common\BillingItemUID;
use App\Common\TariffUID;

use App\Entity\BillingItemReference;
use App\Repository\BillingItemReferenceRepository;
use App\Entity\TariffReference;
use App\Entity\UserBillingObject;
use App\Entity\UserBillingItem;
use App\Factory\CollectorFactory;
use App\Service\CollectorFactoryService;


class UserBillingTest extends KernelTestCase {

    protected function setUp(): void
    {
        // Boot the Symfony kernel
        self::bootKernel();
    }



    public function test_1(): void {

        $collectorFactoryService = static::getContainer()->get('App\Service\CollectorFactoryService');
        $this->assertInstanceOf('App\Service\CollectorFactoryService', $collectorFactoryService);
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $billingItemReferenceRepository = $entityManager->getRepository(BillingItemReference::class);

        // work starts here

        $billingItemReference = $billingItemReferenceRepository->find(BillingItemUID::ColocationSingleRack);

        $ubo = new UserBillingObject($billingItemReference);
        $items = $ubo->getUserBillingData();
        foreach($items as $item) {
            $collectors = $collectorFactoryService->createCollectors($item);
            foreach ($collectors as $collector) {
                $item->addCollector($collector);
            }
        }

        $entityManager->persist($ubo);
        $entityManager->flush();

    }

}
