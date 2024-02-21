<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Form\PublicReviewType;
use App\Repository\ReviewRepository;
use App\Repository\UserCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicProductController extends AbstractController
{
    #[Route('/public/product/{id}', name: 'app_public_product')]
    public function index(
        Product $product,
        Request $request,
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository,
        UserCollectionRepository $userCollectionRepository
    ): Response {
        $review = new Review();
        $review->setPriductReview($product);
        $review->setUserReview($this->getUser());
        $review->setModerated('suspended');
        $form = $this->createForm(PublicReviewType::class, $review);
        $form->handleRequest($request);

        $reviews = $reviewRepository->findBy([], ['createdAt' => 'DESC']);
        $userColletion = $userCollectionRepository->findBy(
            ['user' => $this->getUser()]
        );

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_public_product', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
        }

        if (isset($_POST['selectOption'])) {
            $collection = $userCollectionRepository->findOneBy(
                ['id' => $_POST['selectOption']]
            );
            $collection->addProduct($product);
            $entityManager->persist($collection);
            $entityManager->flush();

            return $this->redirectToRoute('public_collection', ['id' => $collection->getId()], Response::HTTP_SEE_OTHER);
        }


        return $this->render('pages/public_product/index.html.twig', [
            'product' => $product,
            'reviews' => $reviews,
            'form' => $form->createView(),
            'userColletion' => $userColletion,
        ]);
    }
}
