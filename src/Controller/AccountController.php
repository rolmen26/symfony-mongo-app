<?php

namespace App\Controller;

use App\Document\User;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{

    /**
     * @param DocumentManager $dm
     * @return JsonResponse
     */
    #[Route('/api/register', name: 'register_user', methods: ['POST'])]
    public function registerAction(DocumentManager $dm): JsonResponse
    {
        $data = file_get_contents('php://input');

        if(!$data) {
            return $this->json(['error' => 'No data received'], 400);
        }

        $data = json_decode($data, true);

        /** @var UserRepository $userRepository  */
        $userRepository = $dm->getRepository(User::class);
        $userExists = $userRepository->userExists($data['email']);

        if (!$userExists) {
            $user = new User($data['email'], $data['password']);

            $dm->persist($user);
            try { //Here is where the data will be saved
                $dm->flush();
            } catch (MongoDBException $ex) {
                return $this->json(['error' => 'Error creating the user', 'message' => $ex->getMessage()], 500);
            }

            return $this->json([
                'message' => 'The user was created successfully',
                'userId' => $user->getId()]);
        } else {
            return $this->json(['error' => 'Email already exists'], 200);
        }
    }

    /**
     * Request that asks for the DB if there's a user with the e-mail and password
     *
     * @param DocumentManager $dm
     * @return JsonResponse
     */
    #[Route('/api/login', name: 'find_user', methods: ['POST'])]
    public function loginAction(DocumentManager $dm): JsonResponse
    {
        $data = file_get_contents('php://input');

        if(!$data) {
            return $this->json(['error' => 'No data received'], 400);
        }

        $data = json_decode($data, true);

        try {
            /** @var UserRepository $userRepository */
            $userRepository = $dm->getRepository(User::class);
            $userFound = $userRepository->findLoginUser($data['email'], $data['password']);
        } catch (LockException|MappingException $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
        if (!$userFound) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json(['user' => $userFound->getId()]);
    }
}
