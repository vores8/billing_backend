<?php

namespace App\Tests\Functional;

// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

use App\Common\BillingItemUID;
use App\Common\CollectorUID;
use App\Common\TariffUID;

use App\Entity\BillingItemReference;
use App\Repository\BillingItemReferenceRepository;
use App\Entity\TariffReference;
use App\Entity\UserBillingObject;
use App\Entity\UserBillingItem;
use App\Entity\UserTariff;
use App\Entity\User;
use App\Entity\CollectorData;
use App\Common\CollectorFactory;

class UserBillingTest extends KernelTestCase {

    protected function setUp(): void
    {
        // Boot the Symfony kernel
        self::bootKernel();
    }

    public function testFlatRate(): void {

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = new User();
        $entityManager->persist($user);
        
        // work starts here

        $flat_rate_value = 2000;

        $billingItemReference = $entityManager->getRepository(BillingItemReference::class)->find(BillingItemUID::ColocationServer);
        $ubo = new UserBillingObject($user, $billingItemReference);
        CollectorFactory::createCollectors($ubo->getUserBillingItem(), $entityManager);

        $entityManager->persist($ubo);
        $entityManager->flush();

        $colocation_item_id = $ubo->getUserBillingItem();

        $this->assertNotNull($colocation_item_id);

        $colocation_item = $entityManager->getRepository(UserBillingItem::class)->find($colocation_item_id);
        $collector = $colocation_item->getCollectorByUID(CollectorUID::CollectorStatic);
        $flat_tariff_reference = $entityManager->getRepository(TariffReference::class)->find(TariffUID::Flat);
        $flat_tariff = new UserTariff($flat_tariff_reference);
        $flat_tariff->setParam('rate', $flat_rate_value);
        $collector->setTariff($flat_tariff);

        $amount = $ubo->getAmountDue(1, 10);


        $this->assertEquals($flat_rate_value, $amount);

    }

    public function testCollectorDynamic(): void {

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // work starts here

        $flat_rate_value = 200;
        $power_rate_value = 100;

        $user = new User();
        $entityManager->persist($user);

        $billingItemReference = $entityManager->getRepository(BillingItemReference::class)->find(BillingItemUID::ColocationServer);
        $ubo = new UserBillingObject($user, $billingItemReference);
        CollectorFactory::createCollectors($ubo->getUserBillingItem(), $entityManager);
        $entityManager->persist($ubo);
        $entityManager->flush();

        $root_item = $ubo->getUserBillingItem();
        $collector = $root_item->getCollectorByUID(CollectorUID::CollectorDynamic);

        $data = new CollectorData();
        $data->setTimestamp(1);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(2);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(3);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(4);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(5);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(6);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(7);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(8);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(9);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $data = new CollectorData();
        $data->setTimestamp(10);
        $data->setAmount(1);
        $collector->addCollectorData($data);

        $power_tariff_reference = $entityManager->getRepository(TariffReference::class)->find(TariffUID::Average);
        $power_tariff = new UserTariff($power_tariff_reference);
        $power_tariff->setParam('rate', $power_rate_value);
        $collector->setTariff($power_tariff);

        $collector = $root_item->getCollectorByUID(CollectorUID::CollectorStatic);

        $flat_tariff_reference = $entityManager->getRepository(TariffReference::class)->find(TariffUID::Flat);
        $flat_tariff = new UserTariff($flat_tariff_reference);
        $flat_tariff->setParam('rate', $flat_rate_value);
        $collector->setTariff($flat_tariff);

        $amount = $ubo->getAmountDue(1, 5);

        $this->assertEquals($flat_rate_value + ((1 + 1 + 1 + 1 + 1) / 5) * $power_rate_value, $amount);

    }

}
