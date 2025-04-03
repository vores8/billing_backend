<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\TariffReference;

final class TariffReferenceController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/tariff_reference', name: 'app_tariff_reference')]
    public function index(): JsonResponse
    {

        $repository = $this->entityManager->getRepository(TariffReference::class);
        $entities = $repository->findAll();

        $data = [];
        foreach ($entities as $entity) {
            $data[] = [
                'uid' => $entity->getUid(),
                'title' => $entity->getTitle(),
                'params' => $entity->getParams(),
            ];
        }

        return new JsonResponse($data);
    }
}
