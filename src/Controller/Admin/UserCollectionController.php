<?php

namespace App\Controller\Admin;

use App\Entity\UserCollection;
use App\Form\UserCollectionType;
use App\Repository\UserCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/collection')]
class UserCollectionController extends AbstractController
{
    #[Route('/', name: 'app_user_collection_index', methods: ['GET'])]
    public function index(UserCollectionRepository $userCollectionRepository): Response
    {
        return $this->render('admin/user_collection/index.html.twig', [
            'user_collections' => $userCollectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_collection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userCollection = new UserCollection();
        $form = $this->createForm(UserCollectionType::class, $userCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_collection/new.html.twig', [
            'user_collection' => $userCollection,
            'form' => $form,
            'action' => 'new'
        ]);
    }

    #[Route('/{id}', name: 'app_user_collection_show', methods: ['GET'])]
    public function show(UserCollection $userCollection): Response
    {
        return $this->render('admin/user_collection/show.html.twig', [
            'user_collection' => $userCollection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserCollection $userCollection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserCollectionType::class, $userCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_collection/new.html.twig', [
            'user_collection' => $userCollection,
            'form' => $form,
            'action' => 'update'
        ]);
    }

    #[Route('/{id}', name: 'app_user_collection_delete', methods: ['POST'])]
    public function delete(Request $request, UserCollection $userCollection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $userCollection->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_collection_index', [], Response::HTTP_SEE_OTHER);
    }
}
