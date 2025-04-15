<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Common\CollectorUID;
use App\Common\TariffUID;
use App\Entity\BillingItemReference;
use App\Entity\UserBillingObject;
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

    #[Route('/user/billingitem/create/{user_id}', name: 'app_user_billing_item', methods: ['POST'])]
    public function create($user_id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $uid = $data['uid'] ?? null; // Use null coalescing operator to avoid errors

        // Validate the data (optional but recommended)
        if (!$uid) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }
        $billingItemReference = $this->entityManager->getRepository(BillingItemReference::class)->find($uid);

        if (!$billingItemReference) {
            return new JsonResponse(['error' => 'Billing item reference not found'], 400);
        }

        $ubo = $this->entityManager->getRepository(UserBillingObject::class)->findOneBy(['user' => $user_id]);

        if ($ubo == null) {
            $user =  $this->entityManager->getRepository(User::class)->find($user_id);
            $ubo = new UserBillingObject($user, $billingItemReference);
            foreach($ubo->getUserBillingItems() as $item) {
                CollectorFactory::createCollectors($item, $this->entityManager);
            }
            $this->entityManager->persist($ubo);
            $this->entityManager->flush();    
        }


        $root_item = $ubo->getUserBillingItems()->first();
        $collector = $root_item->getCollectorByUID(CollectorUID::PowerUsage);

        $power_tariff_reference = $this->entityManager->getRepository(TariffReference::class)->find(TariffUID::PowerUsage);
        $power_tariff = new UserTariff($power_tariff_reference);
        // $power_tariff->setParam('rate', $power_rate_value);
        $collector->setTariff($power_tariff);

        $collector = $root_item->getCollectorByUID(CollectorUID::RackSpace);

        $flat_tariff_reference = $this->entityManager->getRepository(TariffReference::class)->find(TariffUID::Flat);
        $flat_tariff = new UserTariff($flat_tariff_reference);
        // $flat_tariff->setParam('rate', $flat_rate_value);
        $collector->setTariff($flat_tariff);

        $this->entityManager->persist($ubo);
        $this->entityManager->flush();

        $userBillingItems = [];
        foreach($ubo->getUserBillingItems() as $item) {
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
            $userBillingItems[]  = [
                'id' => $item->getId(),
                'reference' => $item->getReference()->getUid(),
                'title' => $item->getReference()->getTitle(),
                'collectors' => $collectorData,
            ];
        }


        return $this->json([
            'id' => $ubo->getId(),
            'billing_items' => $userBillingItems,
        ]);
    }


    #[Route('/user/billingitem/list/{user_id}', name: 'app_user_billing_item_list', methods: ['GET'])]
    public function list($user_id): JsonResponse
    {

        $ubo = $this->entityManager->getRepository(UserBillingObject::class)->findOneBy(['user' => $user_id]);

        $userBillingItems = [];
        foreach($ubo->getUserBillingItems() as $item) {
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
            $userBillingItems[]  = [
                'id' => $item->getId(),
                'reference' => $item->getReference()->getUid(),
                'title' => $item->getReference()->getTitle(),
                'collectors' => $collectorData,
            ];
        }

        return $this->json([
            'id' => $ubo->getId(),
            'billing_items' => $userBillingItems,
        ]);
    }

    #[Route('/user/billingitem/get_amount/{user_id}', name: 'app_user_billing_item_amount', methods: ['GET'])]
    public function getAmountDue($user_id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $ubo = $this->entityManager->getRepository(UserBillingObject::class)->findOneBy(['user' => $user_id]);

        $amountDue = [];
        foreach($ubo->getUserBillingItems() as $item) {
            $amountDue[]  = [
                'id' => $item->getId(),
                'amount' => $item->getAmountDue($startDate, $endDate),
            ];
        }

        return $this->json([
            'id' => $ubo->getId(),
            'amount_due' => $amountDue,
        ]);
    }


}
