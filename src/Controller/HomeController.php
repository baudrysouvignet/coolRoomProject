<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserCollectionRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProductRepository $productRepository,
        UserCollectionRepository $userCollectionRepository
    ): Response {

        return $this->render('pages/home/index.html.twig', [
            'products' => $productRepository->findBy([], limit: 8),
            'collections' => $userCollectionRepository->collectionsWithProduct(limit: 4),
        ]);
    }

    #[Route('/produits', name: 'app_product')]
    public function products(
        ProductRepository $productRepository,
    ): Response {

        return $this->render('pages/home/product.html.twig', [
            'products' => $productRepository->findBy([]),
        ]);
    }

    #[Route('/collections', name: 'app_collections')]
    public function collection(
        UserCollectionRepository $userCollectionRepository
    ): Response {

        return $this->render('pages/home/collection.html.twig', [
            'collections' => $userCollectionRepository->collectionsWithProduct(),
        ]);
    }
}
