<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Common\CollectorUID;
use App\Common\TariffUID;
use App\Common\BillingItemUID;
use App\Entity\BillingItemReference;
use App\Entity\UserBillingObject;
use App\Entity\UserBillingItem;
use App\Entity\UserTariff;
use App\Entity\TariffReference;
use App\Common\CollectorFactory;
use App\Entity\User;

final class UserBillingItemController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


// functions to populate user billing items    

    function populateItemColocationServer(UserBillingItem $item) : UserBillingItem
    {
        $collector = $item->getCollectorByUID(CollectorUID::CollectorDynamic);

        $power_tariff_reference = $this->entityManager->getRepository(TariffReference::class)->find(TariffUID::AverageRateAbove);
        $power_tariff = new UserTariff($power_tariff_reference);
        $collector->setTariff($power_tariff);

        $collector = $item->getCollectorByUID(CollectorUID::CollectorStatic);

        $flat_tariff_reference = $this->entityManager->getRepository(TariffReference::class)->find(TariffUID::Flat);
        $flat_tariff = new UserTariff($flat_tariff_reference);
        $collector->setTariff($flat_tariff);

        return $item;
    }

    function populateItemColocationPrivateRack(UserBillingItem $item) : UserBillingItem
    {
        $collector = $item->getCollectorByUID(CollectorUID::CollectorDynamic);

        $power_tariff_reference = $this->entityManager->getRepository(TariffReference::class)->find(TariffUID::AverageRateAbove);
        $power_tariff = new UserTariff($power_tariff_reference);
        $collector->setTariff($power_tariff);

        return $item;
    }

    function populateItemColocationPrivateBlock(UserBillingItem $item, int $factor) : UserBillingItem
    {
        $collector = $item->getCollectorByUID(CollectorUID::CollectorDynamic);

        $power_tariff_reference = $this->entityManager->getRepository(TariffReference::class)->find(TariffUID::AverageFactorAbove);
        $power_tariff = new UserTariff($power_tariff_reference, $factor);
        $collector->setTariff($power_tariff);

        return $item;
    }


    #[Route('/user/billingitem/create/{user_id}', name: 'app_user_billing_item', methods: ['POST'])]
    public function create($user_id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $uid = $data['uid'] ?? null; // Use null coalescing operator to avoid errors
        $factor = $data['factor'] ?? 1;

        if (!$uid) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }
        $billingItemReference = $this->entityManager->getRepository(BillingItemReference::class)->find($uid);

        if (!$billingItemReference) {
            return new JsonResponse(['error' => 'Billing item reference not found'], 400);
        }

        // $ubo = $this->entityManager->getRepository(UserBillingObject::class)->findOneBy(['user' => $user_id]);

        // if ($ubo == null) {
            $user =  $this->entityManager->getRepository(User::class)->find($user_id);
            $ubo = new UserBillingObject($user, $billingItemReference, $factor);
            foreach($ubo->getUserBillingItems() as $item) {
                CollectorFactory::createCollectors($item, $this->entityManager);
            }
            $this->entityManager->persist($ubo);
            $this->entityManager->flush();    
        // }


        $root_item = $ubo->getUserBillingItem();

        if ($root_item->getBillingItemReference()->getUid() == BillingItemUID::ColocationShared) {
            $root_item = $this->populateItemColocationShared($root_item);
        } elseif ($root_item->getBillingItemReference()->getUid() == BillingItemUID::ColocationPrivateRack) {
            $root_item = $this->populateItemColocationPrivateRack($root_item);
        }  elseif ($root_item->getBillingItemReference()->getUid() == BillingItemUID::ColocationPrivateBlock) {
            $root_item = $this->populateItemColocationPrivateBlock($root_item, $factor);
        }

        $this->entityManager->persist($ubo);
        $this->entityManager->flush();

        $item = $ubo->getUserBillingItem();
        $collectorData = [];
        foreach($item->getCollectors() as $collector) {

            $tariff = $collector->getTariff();
            $tariffData = [
                'id' => $tariff->getId(),
                'reference' => $tariff->getReference()->getUid(),
                'title' => $tariff->getReference()->getTitle(),
                'params' => $tariff->getParams(),
            ];

            $collectorData[] = [
                'id' => $collector->getId(),
                'reference' => $collector->getUid(),
                'tariff' => $tariffData,
            ];
        }
        $userBillingItem  = [
            'id' => $item->getId(),
            'reference' => $item->getReference()->getUid(),
            'title' => $item->getReference()->getTitle(),
            'collectors' => $collectorData,
        ];
    return $this->json([
            'id' => $ubo->getId(),
            'billing_item' => $userBillingItem,
        ]);
    }


    #[Route('/user/billingitem/list/{user_id}', name: 'app_user_billing_item_list', methods: ['GET'])]
    public function list($user_id): JsonResponse
    {

        $ubo = $this->entityManager->getRepository(UserBillingObject::class)->findOneBy(['user' => $user_id]);

        $item = $ubo->getUserBillingItem();
        $collectorData = [];
        foreach($item->getCollectors() as $collector) {

            $tariff = $collector->getTariff();
            $tariffData = [
                'id' => $tariff->getId(),
                'reference' => $tariff->getReference()->getUid(),
                'title' => $tariff->getReference()->getTitle(),
                'params' => $tariff->getParams(),
            ];

            $collectorData[] = [
                'id' => $collector->getId(),
                'reference' => $collector->getUid(),
                'tariff' => $tariffData,
            ];
        }
        $userBillingItem  = [
            'id' => $item->getId(),
            'reference' => $item->getReference()->getUid(),
            'title' => $item->getReference()->getTitle(),
            'collectors' => $collectorData,
        ];

        return $this->json([
            'id' => $ubo->getId(),
            'billing_item' => $userBillingItem,
        ]);
    }

    #[Route('/user/billingitem/get_amount/{user_id}', name: 'app_user_billing_item_amount', methods: ['GET'])]
    public function getAmountDue($user_id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $ubo = $this->entityManager->getRepository(UserBillingObject::class)->findOneBy(['user' => $user_id]);

        $item = $ubo->getUserBillingItem();
        $amountDue  = [
            'id' => $item->getId(),
            'amount' => $item->getAmountDue($startDate, $endDate),
        ];

        return $this->json([
            'id' => $ubo->getId(),
            'amount_due' => $amountDue,
        ]);
    }
}
