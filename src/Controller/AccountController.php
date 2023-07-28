<?php

namespace App\Controller;

use App\Document\User;
use App\Events\ExampleEvent;
use DateTime;
use Doctrine\Common\EventManager;
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
        $data = json_decode($data, true);

        $emailExists = $dm->getRepository(User::class)->userExists($data['email']);

        if (!$emailExists) {
            $user = new User();

            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setCreatedAt(date("Y-m-d H:i:s"));

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
            return $this->json(['error' => 'Email already exists'], 500);
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
        $data = json_decode($data, true);

        try {
            /** @var User|bool $userFound */
            $userFound = $dm->getRepository(User::class)->findLoginUser($data['email'], $data['password']);
        } catch (LockException|MappingException $e) {
            return $this->createNotFoundException($e->getMessage());
        }
        if (!$userFound) {
            return $this->json(['error' => 'User not found'], 500);
        }

        return $this->json(['user' => $userFound->getId()]);
    }
}
