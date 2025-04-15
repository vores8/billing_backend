<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\UserTariff;


final class TariffController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/usertariff/{id}', name: 'app_tariff_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $tariff = $this->entityManager->getRepository(UserTariff::class)->find($id);

        $tariffData = [
            'id' => $tariff->getId(),
            'reference' => $tariff->getReference()->getUid(),
            'title' => $tariff->getReference()->getTitle(),
            'params' => $tariff->getParams(),
        ];



        return $this->json($tariffData);
    }

    #[Route('/admin/usertariff/{id}/param', name: 'app_tariff_set_param', methods: ['POST'])]
    public function set_param($id, Request $request): JsonResponse
    {
        $tariff = $this->entityManager->getRepository(UserTariff::class)->find($id);

        $data = json_decode($request->getContent(), true);

        $tariff = $this->entityManager->getRepository(UserTariff::class)->find($id);

        $keys = array_keys($data);

        foreach($keys as $key) {
            if ($key == 'id') {
                continue;
            }
            if (!isset($tariff->getParams()[$key])) {
                return new JsonResponse(['error' => 'invalid tariff param'], 400);
            }
            $value = $data[$key];
            $tariff->setParam($key, $value);
        }

        $this->entityManager->persist($tariff);
        $this->entityManager->flush();


        $tariffData = [
            'id' => $tariff->getId(),
            'reference' => $tariff->getReference()->getUid(),
            'title' => $tariff->getReference()->getTitle(),
            'params' => $tariff->getParams(),
        ];

        return $this->json($tariffData);
    }

}
