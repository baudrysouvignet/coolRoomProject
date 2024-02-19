<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use App\Repository\UserCollectionRepository;
use App\Repository\UserRepository;
use App\Repository\VueRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class HeaderService
{
    private $userRepository;
    private $userCollectionRepository;
    private $security;

    public function __construct(
        UserRepository $userRepository,
        UserCollectionRepository $userCollectionRepository,
        Security $security
    ) {
        $this->userRepository = $userRepository;
        $this->userCollectionRepository = $userCollectionRepository;
        $this->security = $security;
    }

    /**
     * This function recover the actual request and recover headerDate
     *
     * @return array
     */
    public function getHeaderData()
    {
        $headerData = [
            'collections' => $this->getCollections(),
        ];

        return $headerData;
    }

    private function getCollections()
    {
        $collections = $this->userCollectionRepository->findBy(['user' => $this->security->getUser()]);

        return $collections;
    }
}
