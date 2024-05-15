<?php

namespace App\ClientManagement\Infrastructure\Controller;

use App\ClientManagement\Application\Command\RegisterClientCommand;
use App\ClientManagement\Application\Service\ClientRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'client_register', methods: ['POST'])]

final class RegisterClientController extends AbstractController
{

    private ClientRegistrationService $clientRegistrationService;

    public function __construct(ClientRegistrationService $clientRegistrationService)
    {
        $this->clientRegistrationService = $clientRegistrationService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new RegisterClientCommand($data['name'], $data['email'], $data['password']);

            $client = $this->clientRegistrationService->registerClient($command);

            return $this->json([
                'message' => 'Client registered successfully',
                'client' => [ 'uuid' => $client->getUuid()]
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}