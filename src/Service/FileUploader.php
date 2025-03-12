<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

//Service pour uploader des fichiers.

class FileUploader
{

    private $targetDirectory;
    private $targetDirectoryProduct;
    private $targetDirectoryProductAudio;
    private $slugger;

    public function __construct($targetDirectory, $targetDirectoryProduct, SluggerInterface $slugger, $targetDirectoryProductAudio)
    {
        $this->targetDirectory = $targetDirectory;
        $this->targetDirectoryProduct = $targetDirectoryProduct;
        $this->slugger = $slugger;
        $this->targetDirectoryProductAudio = $targetDirectoryProductAudio;
    }

    private function uploadFile(?UploadedFile $file, string $directory): ?string
    {
        if ($file === null) {
            return null;
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($directory, $fileName);
            return $fileName;
        } catch (FileException $e) {
            return null;
        }
    }
    //Upload des images des categories.
    public function uploadCategory(UploadedFile $file): ?string
    {
        return $this->uploadFile($file, $this->targetDirectory);
    }

    //Upload des images des produits.
    public function uploadProductPhoto(UploadedFile $file): ?string
    {
        return $this->uploadFile($file, $this->targetDirectoryProduct);
    }


    //Upload des audios des produits.
    public function uploadProductAudio(?UploadedFile $file): ?string
    {
        return $this->uploadFile($file, $this->targetDirectoryProductAudio);
    }

    //Getters.
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    public function getTargetDirectoryProduct()
    {
        return $this->targetDirectoryProduct;
    }
    public function getTargetDirectoryAudio()
    {
        return $this->targetDirectoryProductAudio;
    }
}
