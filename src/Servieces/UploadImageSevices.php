<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadImageSevices
{
    private $imageFile;
    private SluggerInterface $slugger;
    private string $imageDirectory;

    public function __construct(
        $imageFile,
        string $imageDirectory,
        SluggerInterface $slugger
    ) {
        $this->imageDirectory = $imageDirectory;
        $this->imageFile = $imageFile;
        $this->slugger = $slugger;
    }

    public function uploadImage(): string
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
