<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

final class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/user/create', name: 'app_user_create')]
    public function create(): JsonResponse
    {
        $user = new User();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'id' => $user->getId(),
        ]);
    }
    
    #[Route('/admin/user/list', name: 'app_user_list')]
    public function list(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        $userList = [];

        foreach ($users as $user) {
            $userList[] = [
                'id' => $user->getId(),
            ];

        }

        return $this->json($userList);
    }
}
