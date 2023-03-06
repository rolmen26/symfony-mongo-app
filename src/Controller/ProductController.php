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
    public function index(Request $request, DocumentManager $documentManager): Response
    {
        $product = new Product();
        var_dump($request->request->get('Product'));
        die();
        $product->setName('Juan');
        $product->setPrice('9.999');
        $documentManager->persist($product);
        $documentManager->flush();

        return new Response('Created product id ' . $product->getId());
    }

    #[Route('/products', name: 'product', methods: ['GET'])]
    public function showProducts(DocumentManager $documentManager): JsonResponse
    {
        $products = $documentManager->getDocumentCollection(Product::class)->find();

        return $this->json([
            'productos' => $products->toArray()
        ]);
    }
}
