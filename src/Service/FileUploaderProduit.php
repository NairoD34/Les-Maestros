<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

//Service pour uploader les images des produits.
class FileUploaderProduit
{
    private $targetDirectory;
    private $slugger;
    public function __construct($targetDirectoryProduct, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectoryProduct;
        $this->slugger = $slugger;
    }

    //Upload des images des produits.
    public function upload(UploadedFile $file)
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

    //Getters.
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
