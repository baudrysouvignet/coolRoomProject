<?php

namespace App\Controller;

use App\Entity\UserCollection;
use App\Form\PublicUserCollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/collection')]
class PublicCollectionController extends AbstractController
{
    #[Route('/{id}', name: 'public_collection')]
    public function index(
        UserCollection $userCollection
    ): Response {
        $products = $userCollection->getProduct();

        return $this->render('pages/public_collection/index.html.twig', [
            'collection' => $userCollection,

            'products' => $products,
        ]);
    }

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

            return $this->redirectToRoute('app_user_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/public_collection/new.html.twig', [
            'user_collection' => $userCollection,
            'form' => $form,
        ]);
    }
}
