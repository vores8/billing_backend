<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserBillingItemController extends AbstractController
{
    #[Route('/user/billingitem/create/{%1}', name: 'app_user_billing_item', methods: ['GET'])]
    public function create(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserBillingItemController.php',
        ]);
    }
}
