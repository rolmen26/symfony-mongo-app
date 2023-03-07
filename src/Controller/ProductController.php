<?php

namespace App\Controller;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @throws MongoDBException
     */
    #[Route('/product', name: 'new_product', methods: ['POST'])]
    public function index(Request $request, DocumentManager $documentManager): JsonResponse
    {
        $product = new Product();

        $dataRequest = json_decode($request->getContent(), true);

        $product->setName($dataRequest['name']);
        $product->setPrice($dataRequest['price']);
        $documentManager->persist($product);
        $documentManager->flush();

        return $this->json([
            'status' => true,
            'id' => $product->getId(),
        ]);
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/products', name: 'product', methods: ['GET'])]
    public function showProducts(DocumentManager $documentManager): JsonResponse
    {
        $products = $documentManager->getDocumentCollection(Product::class)
            ->find()
            ->toArray();

        return $this->json([
            'productos' => $products
        ]);

    }
}
