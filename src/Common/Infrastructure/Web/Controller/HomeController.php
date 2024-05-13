<?php

namespace App\Common\Infrastructure\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'home', methods: ['GET'])]
final class HomeController extends AbstractController {

    public function __invoke(): JsonResponse {
        return $this->json(['message' => 'Welcome to the API!']);
    }
}