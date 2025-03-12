<?php

namespace App\Service\BackOffice;

use App\Entity\Category;
use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\Users;
use App\Form\SalesFormType;
use App\Entity\Sales;
use App\Form\AdminCategoryFormType;
use App\Form\AdminOrderFormType;
use App\Form\AdminFormType;
use App\Form\AdminProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\PhotosRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// Classe pour gérer les opérations liées aux formulaires dans le back-office.
class FormHandlerService
{
    private $formFactory;
    private $upload;
    private $em;


    // Constructeur de la classe FormHandlerService.
    public function __construct(FormFactoryInterface $formFactory, FileUploader $fileUploader, EntityManagerInterface $emi)
    {
        $this->formFactory = $formFactory;
        $this->upload = $fileUploader;
        $this->em = $emi;
    }

    // Methode pour gérer les opérations liées aux ventes dans le back-office.
    public function handleSales(Request $request, Sales $sales)
    {
        $form = $this->formFactory->create(SalesFormType::class, $sales);
        $validate = false;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($sales);
            $this->em->flush();
            $validate = true;
        }

        return [
            'validate' => $validate,
            'form' => $form,
        ];
    }

    // Methode pour gérer les opérations liées aux produits dans le back-office.
    public function handleProduct($update, Request $request, Product $product, PhotosRepository $photoRepo)
    {
        $form = $this->formFactory->create(AdminProductFormType::class, $product, ['is_update' => $update]);
        $validate = false;

        $form->handleRequest($request);
        $audio_name = "";
        $photo_name = "";
        
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form['upload_file']->getData();
            $audio = $form['upload_audio']->getData();

            if (!$update && !is_file($photo)) {
                return [
                    'condition' => $validate,
                    'form' => $form,
                ];
            }

            $category = $form['category']->getData();
            $product->setCategory($category);

            if ($audio && is_file($audio)) {
                $audio_name = $this->upload->uploadProductAudio($audio);
                if ($update) {
                    $currentAudio = $product->getAudio();
                    if ($currentAudio) {
                        $old_audio_name = explode('/', $currentAudio);
                        unlink($this->upload->getTargetDirectoryAudio() . '/' . end($old_audio_name));
                    }
                }
                $product->setAudio('/upload/audio_product/' . $audio_name);
            }

            if ($update) {
                if ($photo) {
                    $actualPhoto = $product->getPhotos(); //Récupère une persistent collection 
                    $URLPhoto = $actualPhoto->getValues()[0]->getURLPhoto(); //Transforme la collection en array, puis récupère l'URL de la photo à l'index 0
                    if ($URLPhoto) {
                        $old_photo_name = explode('/', $URLPhoto);
                        unlink($this->upload->getTargetDirectoryProduct() . '/' . end($old_photo_name));
                    }
                    $photo_name = $this->upload->uploadProductPhoto($photo);
                    $photoRepo->updatePhotoInProduct($product->getId(), '/upload/photo_product/' . $photo_name);
                }
                $this->em->persist($product);
                $this->em->flush();
                $validate = true;
            } else {
                if ($photo) {
                    $photo_name = $this->upload->uploadProductPhoto($photo);
                    $this->em->persist($product);
                    $this->em->flush();
                    $photoRepo->insertPhotoWithProduct($product->getId(), '/upload/photo_product/' . $photo_name);
                    $validate = true;
                }
            }
        }

        return [
            'condition' => $validate,
            'form' => $form,
        ];
    }

    // Methode pour gérer les opérations liées aux administrateurs dans le back-office.
    public function handleAdmin(bool $update, Request $request, Users $admin, UserPasswordHasherInterface $adminPasswordHasher, ValidatorInterface $validatorInterface)
    {
        $form = $this->formFactory->create(AdminFormType::class, $admin);
        $form->handleRequest($request);
        $validate = false;
        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            if ($update !== true) {
                $admin->setRoles(['ROLE_ADMIN']);
                $admin->setPassword(
                    $adminPasswordHasher->hashPassword(
                        $admin,
                        $form->get('password')->getData()
                    )
                );
            }
            $this->em->persist($admin);
            $this->em->flush();
            $validate = true;
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = $validatorInterface->validate($admin);/* 
            $errorsString = (string) $errors;
            dd($errors); */
        }

        return [
            'validate' => $validate,
            'form' => $form,
            'errors' => $errors,
        ];
    }

    // Methode pour gérer les opérations liées aux commandes dans le back-office.
    public function handleOrder(Request $request, Orders $order)
    {
        $form = $this->formFactory->create(AdminOrderFormType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->em->persist($order);
            $this->em->flush();
            return [
                'validate' => true,
                'form' => $form,
            ];
        }
        return [
            'validate' => false,
            'form' => $form,
        ];
    }

    // Methode pour gérer les opérations liées aux categories dans le back-office.
    public function handleCategory(bool $update, Request $request, Category $category, PhotosRepository $photoRepo, ?CategoryRepository $categoryRepo)
    {
        $form = $this->formFactory->create(AdminCategoryFormType::class, $category, ['is_update' => $update]);
        $validate = false;

        $form->handleRequest($request);
        $photo_name = "";
        
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form['upload_file']->getData();

            if (!$update && !is_file($photo)) {
                return [
                    'validate' => $validate,
                    'form' => $form,
                ];
            }

            $parentCategory = $form['parent_category']->getData();
            $category->setParentCategory($parentCategory);

            if ($update) {
                if ($photo) {
                    $actualPhoto = $category->getPhotos(); //Récupère une persistent collection 
                    $URLPhoto = $actualPhoto->getValues()[0]->getURLPhoto(); //Transforme la collection en array, puis récupère l'URL de la photo à l'index 0
                    if ($URLPhoto) {
                        $old_photo_name = explode('/', $URLPhoto);
                        unlink($this->upload->getTargetDirectory() . '/' . end($old_photo_name));
                    }
                    $photo_name = $this->upload->uploadCategory($photo);
                    $photoRepo->updatePhotoInCategory($category->getId(), '/upload/photo_category/' . $photo_name);
                }
                $this->em->persist($category);
                $this->em->flush();
                $validate = true;
            } else {
                if ($photo) {
                    $photo_name = $this->upload->uploadCategory($photo);
                    $this->em->persist($category);
                    $this->em->flush();
                    $photoRepo->insertPhotoWithCategorie($category->getId(), '/upload/photo_category/' . $photo_name);
                    $validate = true;
                }
            }
        }

        return [
            'validate' => $validate,
            'form' => $form,
        ];
    }
}
