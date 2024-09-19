<?php

namespace App\ClientManagement\Infrastructure\Controller;

use App\ClientManagement\Application\Command\RegisterClientCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'client_register', methods: ['POST'])]
final class RegisterClientController extends AbstractController
{

    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new RegisterClientCommand($data['name'], $data['email'], $data['password']);

            $this->commandBus->dispatch($command);

            return $this->json(['message' => 'Client registered successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}