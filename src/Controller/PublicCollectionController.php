<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\UserCollection;
use App\Form\PublicUserCollectionType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/user/collection')]
class PublicCollectionController extends AbstractController
{
    #[Route('/new', name: 'add_public_collection')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $userCollection = new UserCollection();
        $userCollection->setUser($this->getUser());
        $form = $this->createForm(PublicUserCollectionType::class, $userCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($userCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/public_collection/new.html.twig', [
            'user_collection' => $userCollection,
            'form' => $form,
        ]);
    }

    #[Route('/remove/{id}/{product}', name: 'remove_public_collection')]
    public function remove(
        UserCollection $userCollection,
        $product,
        EntityManagerInterface $entityManager
    ): Response {
        if ($userCollection->getUser() === $this->getUser()) {
            $product = $entityManager->getRepository(Product::class)->find($product);
            $userCollection->removeProduct($product);
            $entityManager->persist($userCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('public_collection', ['id' => $userCollection->getId()]);
    }


    #[Route('/delete/{id}', name: 'delete_public_collection')]
    public function delete(
        UserCollection $userCollection,
        EntityManagerInterface $entityManager
    ): Response {
        if ($userCollection->getUser() === $this->getUser()) {
            $entityManager->remove($userCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/{id}', name: 'public_collection')]
    public function index(
        UserCollection $userCollection,
        CategoryRepository $categoryRepository
    ): Response {
        $products = $userCollection->getProduct();
        $category = $categoryRepository->findAll();

        if (isset($_GET['cat'])) {
            $productFilter = [];
            foreach ($products as $product) {
                if ($product->getCategory()->getId() == $_GET['cat']) {
                    $productFilter[] = $product;
                }
            }
            if ($_GET['cat'] != -1) {
                $products = $productFilter;
            }
        }

        return $this->render('pages/public_collection/index.html.twig', [
            'collection' => $userCollection,
            'products' => $products,
            'category' => $category
        ]);
    }
}
