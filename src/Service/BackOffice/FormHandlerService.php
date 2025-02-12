<?php

namespace App\Service\BackOffice;

use App\Entity\Admin;
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
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FormHandlerService
{
    private $formFactory;
    private $upload;
    private $em;


    public function __construct(FormFactoryInterface $formFactory, FileUploader $fileUploader, EntityManagerInterface $emi)
    {
        $this->formFactory = $formFactory;
        $this->upload = $fileUploader;
        $this->em = $emi;

    }

    public function handleSales(Request $request, Sales $sales)
    {
        $form = $this->formFactory->create(SalesFormType::class, $sales);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($sales);
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

    public function handleProduct($update, Request $request, Product $product, $photo, ?ProductRepository $productRepo)
    {
        $form = $this->formFactory->create(AdminProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['upload_file']->getData();
            $audio = $form['upload_audio']->getData();
            if ($file) {
                $file_name = $this->upload->uploadProductPhoto($file);
                if ($file_name) // for example
                {
                    $directory = $this->upload->getTargetDirectory();
                    $full_path = $directory . '/' . $file_name;
                } else {
                    echo('une erreur est survenue à l\'image');
                }
            }
            if ($audio) {
                $audio_name = $this->upload->uploadProductAudio($audio);
                if ($audio_name) {
                    $audioDirectory = $this->upload->getTargetDirectoryAudio();
                    $audio_path = $audioDirectory . '/' . $audio_name;
                } else {
                    echo('une erreur est survenue à l\'audio');
                }
                $product->setAudio($audio_path);
            }
            $category = $form['category']->getData();
            $product->setCategory($category);

            if ($update) {
                if ($file) {
                    $photo->updatePhotoInProduct($product->getId(), '/upload/photo_product/' . $file_name);
                }
                $this->em->persist($product);
                $this->em->flush();
            } else {
                $this->em->persist($product);
                $this->em->flush();
                if ($file) {
                    $photo->insertPhotoWithProduct($productRepo->getLastId()->getId(), '/upload/photo_product/' . $file_name);
                }
            }

            return [
                'condition' => true,
                'form' => $form,
            ];

        }

        return [
            'condition' => false,
            'form' => $form,
        ];
    }

    public function handleAdmin(bool $update, Request $request, Users $admin, UserPasswordHasherInterface $adminPasswordHasher)
    {
        $form = $this->formFactory->create(AdminFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($update !== true) {
                $admin->setRoles(['ROLE_ADMIN']);
                $admin->setPassword(
                    $adminPasswordHasher->hashPassword(
                        $admin,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            $this->em->persist($admin);
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

    public function handleCategory(bool $update, Request $request, Category $category, $photo, ?CategoryRepository $categoryRepo)
    {
        $form = $this->formFactory->create(AdminCategoryFormType::class, $category);

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