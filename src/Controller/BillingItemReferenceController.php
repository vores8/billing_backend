<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\BillingItemReference;

final class BillingItemReferenceController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/common/billing_item_reference', name: 'app_billing_item_reference')]
    public function index(): JsonResponse
    {
        $repository = $this->entityManager->getRepository(BillingItemReference::class);
        $entities = $repository->findAll();

        $data = [];
        foreach ($entities as $entity) {
            $data[] = [
                'uid' => $entity->getUid(),
                'title' => $entity->getTitle(),
                'isRoot' => $entity->getIsRoot(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/common/billing_item_reference_root', name: 'app_billing_item_reference_root')]
    public function indexRoot(): JsonResponse
    {

        $repository = $this->entityManager->getRepository(BillingItemReference::class);
        $entities = $repository->findBy(['is_root' => true]);

        $data = [];
        foreach ($entities as $entity) {
            $data[] = [
                'uid' => $entity->getUid(),
                'title' => $entity->getTitle(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/common/billing_item_reference_non_root', name: 'app_billing_item_reference_non_root')]
    public function indexNonRoot(): JsonResponse
    {

        $repository = $this->entityManager->getRepository(BillingItemReference::class);
        $entities = $repository->findBy(['is_root' => false]);

        $data = [];
        foreach ($entities as $entity) {
            $data[] = [
                'uid' => $entity->getUid(),
                'title' => $entity->getTitle(),
            ];
        }

        return new JsonResponse($data);
    }


}
