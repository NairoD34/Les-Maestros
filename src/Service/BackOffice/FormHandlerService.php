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
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function PHPUnit\Framework\isType;

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
    public function handleProduct($update, Request $request, Product $product, PhotosRepository $photoRepo, ?ProductRepository $productRepo)
    {
        $form = $this->formFactory->create(AdminProductFormType::class, $product, ['is_update' => $update]);
        $validate = false;

        $form->handleRequest($request);
        $audio_name = "";
        $photo_name = "";
        
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form['upload_file']->getData();
            $audio = $form['upload_audio']->getData();

            if (!is_file($photo)) {
                return [
                    'condition' => $validate,
                    'form' => $form,
                ];
            }

            $category = $form['category']->getData();
            $product->setCategory($category);

            if ($audio) {
                $audio_name = $this->upload->uploadProductAudio($audio);
                if ($audio_name) {
                    $product->setAudio('/upload/audio_product/' . $audio_name);
                } 
            }

            if ($update) {
                if ($photo) {
                    $file_name = $this->upload->uploadProductPhoto($photo);
                    $photo->updatePhotoInProduct($product->getId(), '/upload/photo_product/' . $file_name);
                }
                $this->em->persist($product);
                $this->em->flush();
                $validate = true;
            } else {
                if ($photo) {
                    $file_name = $this->upload->uploadProductPhoto($photo);
                    $this->em->persist($product);
                    $this->em->flush();
                    $photoRepo->insertPhotoWithProduct($productRepo->getLastId()->getId(), '/upload/photo_product/' . $photo_name);
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
    public function handleCategory(bool $update, Request $request, Category $category, $photo, ?CategoryRepository $categoryRepo)
    {
        $form = $this->formFactory->create(AdminCategoryFormType::class, $category, ['is_update' => $update]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form['upload_file']->getData();
            if ($update) {
                if ($file) {
                    $file_name = $this->upload->uploadCategory($file);

                    if ($file_name) {
                        $directory = $this->upload->getTargetDirectory();
                        $full_path = $directory . '/' . $file_name;
                        if (file_exists($full_path)) {
                            $error = 'une erreur est survenue';
                        }
                    }
                    $this->em->persist($category);
                    $this->em->flush();
                    $photo->updatePhotoInCategory($category->getId(), '/upload/photo_category/' . $file_name);
                }
            } else {
                if ($file) {
                    $file_name = $this->upload->uploadCategory($file);
                    if ($file_name) {
                        $directory = $this->upload->getTargetDirectory();
                        $full_path = $directory . '/' . $file_name;
                    } else {
                        $error = 'une erreur est survenue';
                    }
                }
                $this->em->persist($category);
                $this->em->flush();
                if ($file) {
                    $photo->insertPhotoWithCategorie($categoryRepo->getLastId()->getId(), '/upload/photo_category/' . $file_name);
                }
            }
            return [
                "validate" => true,
                "form" => $form,
            ];
        }
        return [
            "validate" => false,
            "form" => $form,
        ];
    }
}
