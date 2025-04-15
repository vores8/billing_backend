<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\CollectorData;
use App\Entity\Collector;


final class CollectorDataController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/collector/{collector_id}/add', name: 'app_collector_data_add', methods: ['POST'])]
    public function add($collector_id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $timestamp = $data['timestamp'];
        $amount = $data['amount'];
        $collectorData = new CollectorData();
        $collector = $this->entityManager->getRepository(Collector::class)->find($collector_id);

        $collectorData->setCollector($collector);
        $collectorData->setAmount($amount);
        $collectorData->setTimestamp($timestamp);

        $this->entityManager->persist($collectorData);
        $this->entityManager->flush();    

        $collector->addCollectorData($collectorData);
        // $this->entityManager->persist($collector);
        // $this->entityManager->flush();    

        $data =[];

        foreach($collector->getCollectorData() as $collectorData) {
            $data[] = [
                'id' => $collectorData->getId(),
                'amount' => $collectorData->getAmount(),
                'timestamp' => $collectorData->getTimestamp(),
            ];
        }

        return $this->json([
            'id' => $collector->getId(),
            'data' => $data,
        ]);
    }

    #[Route('/admin/collector/{collector_id}/list', name: 'app_collector_data_list', methods: ['GET'])]
    public function list($collector_id): JsonResponse
    {
        $collector = $this->entityManager->getRepository(Collector::class)->find($collector_id);
        $data = [];
        foreach($collector->getCollectorData() as $collectorData) {
            $data[] = [
                'id' => $collectorData->getId(),
                'amount' => $collectorData->getAmount(),
                'timestamp' => $collectorData->getTimestamp(),
            ];
        }

        return $this->json([
            'id' => $collector->getId(),
            'data' => $data,
        ]);
    }

}
