<?php

namespace App\Service\BackOffice;

use App\Entity\Admin;
use App\Entity\Category;
use App\Entity\Orders;
use App\Entity\Product;
use App\Form\SalesFormType;
use App\Entity\Sales;
use App\Form\AdminCategoryFormType;
use App\Form\AdminOrderFormType;
use App\Form\AdminFormType;
use App\Form\AdminProductFormType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
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
            return true;
        }

        return $form;
    }

    public function handleProduct($update, Request $request, Product $product, $photo, ProductRepository $productRepo)
    {
        $form = $this->formFactory->create(AdminProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form['upload_file']->getData();
            $audio = $form['upload_sound']->getData();
            if ($file) {
                $file_name = $this->upload->uploadProduct($file);
                if ($file_name) // for example
                {
                    $directory = $this->upload->getTargetDirectory();
                    $full_path = $directory . '/' . $file_name;
                } else {
                    $error = 'une erreur est survenue';
                }
            }
            if ($audio) {
                $audio_name = $this->upload->uploadProduct($audio);
                if ($audio_name) {
                    
                }
            }
            $category = $form['category']->getData();
            $product->setCategory($category);
            if ($update) {
                $photos = $photo->updatePhotoInProduct($product->getId(), '/upload/photo_product/' . $file_name);
                foreach ($photos as $photoProduct) {
                    $product->addPhoto($photoProduct);
                }
                $this->em->persist($product);
                $this->em->flush();
            } else {
                $this->em->persist($product);
                $this->em->flush();
                $photo->insertPhotoWithProduct($productRepo->getLastId()->getId(), '/upload/photo_product/' . $file_name);
            }

            return true;

        }

        return $form;
    }

    public function handleAdmin(bool $update, Request $request, Admin $admin, UserPasswordHasherInterface $adminPasswordHasher)
    {
        $form = $this->formFactory->create(AdminFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($update !== true) {
                $selectedRoles = $form->get('roles')->getData();
                $admin->setRoles($selectedRoles);
                $admin->setPassword(
                    $adminPasswordHasher->hashPassword(
                        $admin,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            $this->em->persist($admin);
            $this->em->flush();

            return true;
        }
        return $form;
    }

    public function handleOrder(Request $request, Orders $order)
    {
        $form = $this->formFactory->create(AdminOrderFormType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->em->persist($order);
            $this->em->flush();
            return true;
        }
        return $form;
    }

    public function handleCategory(bool $update, Request $request, Category $category, $photo, $categoryRepo)
    {
        $form = $this->formFactory->create(AdminCategoryFormType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form['upload_file']->getData();
            if ($update) {
                if ($file) {
                    $file_name = $this->upload->uploadCategory($file);

                    if ($file_name) // for example
                    {
                        $directory = $this->upload->getTargetDirectory();
                        $full_path = $directory . '/' . $file_name;
                        if (file_exists($full_path)) {
                            $error = 'une erreur est survenue';
                        }
                    }
                    $photos = $photo->updatePhotoInCategory($category->getId(), '/upload/photo_category/' . $file_name);
                    foreach ($photos as $photoProduct) {
                        $category->addPhoto($photoProduct);
                    }
                    $this->em->persist($category);
                    $this->em->flush();
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
                $photo->insertPhotoWithCategorie($categoryRepo->getLastId()->getId(), '/upload/photo_category/' . $file_name);

            }
            return true;

        }
        return $form;

    }
}