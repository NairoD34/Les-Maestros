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

    //Upload des images des categories.
    public function uploadCategory(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return null; // for example
        }
        return $fileName;
    }

    //Upload des images des produits.
    public function uploadProductPhoto(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectoryProduct(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return null; // for example
        }
        return $fileName;
    }

    //Upload des audios des produits.
    public function uploadProductAudio(?UploadedFile $file)
    {
        if ($file === null) {
            return null;
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectoryAudio(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return null; // for example
        }
        return $fileName;
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
