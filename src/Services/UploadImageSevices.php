<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadImageSevices
{
    private $imageFile;
    private SluggerInterface $slugger;
    private string $imageDirectory;
    private $form;
    private $entity;

    public function __construct(
        string $imageDirectory,
        $form,
        $entity,
        SluggerInterface $slugger
    ) {
        $this->imageDirectory = $imageDirectory;
        $this->imageFile = $form->get('image')->getData();
        $this->slugger = $slugger;
        $this->form = $form;
        $this->entity = $entity;
    }

    public function uploadImage(): void
    {
        $imageFile = $this->form->get('image')->getData();
        if ($imageFile) {
            $newFilename = $this->downlaodImage();
            $this->entity->setImage($newFilename);
        }
    }

    private function downlaodImage(): string
    {
        $originalFilename = pathinfo($this->imageFile->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $this->imageFile->guessExtension();

        try {
            $this->imageFile->move(
                $this->imageDirectory,
                $newFilename
            );

            return $newFilename;
        } catch (FileException $e) {
            dd($e->getMessage());
        }
    }
}
