<?php

namespace App\Controller;

use App\Document\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Request that asks for the DB to save a new user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/api/register', name: 'register_user', methods: ['POST'])]
    public function registerAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $user = $this->userService->registerUser($data['email'], $data['password']);

        if (!$user instanceof User) {
            return $this->json($user, 500);
        }

        return $this->json(['user' => $user->getId()]);
    }

    /**
     * Request that asks for the DB to find a user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/api/login', name: 'find_user', methods: ['POST'])]
    public function loginAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userService->findLoginUser($data['email'], $data['password']);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json(['user' => $user->getId()]);
    }
}
