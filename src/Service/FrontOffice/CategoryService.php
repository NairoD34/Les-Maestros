<?php

namespace App\Service\FrontOffice;

use App\Repository\CategorieRepository;
use App\Repository\PhotosRepository;
use Symfony\Component\HttpFoundation\Request;

class CategoryService
{
    public function __construct(
        private CategorieRepository $cateRepo,
        private PhotosRepository $photoRepo,
    ) {
        
    }

    /**
     * returns an array with categories and pictures
     */

    public function CategoryPicture(
        Request $request,
    )
    {
        $categories = $this->cateRepo->searchCategorieParente(
            $request->query->get('libelle', ''),
        );
        
        foreach ($categories as $cate) {

            $photoCate = $this->photoRepo->searchPhotoByCategorie($cate);
            foreach ($photoCate as $photo) {
                $photoURL = $photo->getURLPhoto();
            }
            $dataCate[] = [
                'categorie' => $cate,
                'photos' => $photoURL,
            ];
        }
        return $dataCate;
    }
}