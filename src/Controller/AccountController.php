<?php

namespace App\Controller;

use App\Model\Registration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{

    /**
     * @throws MongoDBException
     */
    #[Route('/user', name: 'new_user', methods: ['POST'])]
    public function registerAction(DocumentManager $dm, Request $request): JsonResponse
    {
        $register = new Registration();

        $register->registerUser($dm, $request);

        return $this->json([
            'message' => 'The user was created successfully',
            'userId' => $register->getUser()->getId(),
        ], 200 );
    }
}
