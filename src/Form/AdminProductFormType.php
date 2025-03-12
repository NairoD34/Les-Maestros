<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Sales;
use App\Entity\TaxRate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

// Classe pour gérer les opérations liées aux produits dans le back-office.
class AdminProductFormType extends AbstractType
{
    //Methode pour construire le formulaire.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isUpdate = $options['is_update'] ?? false;

        $builder
            ->add('title')
            ->add('description')
            ->add('tax_free_price')
            ->add('TaxRate', EntityType::class, [
                'class' => TaxRate::class,
                'choice_label' => 'taxRate',
            ])
            ->add('sales', EntityType::class, [
                'class' => Sales::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => '',
                'empty_data' => null,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
            ])

            //Methode pour ajouter un fichier image.
            ->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => !$isUpdate,
                'constraints' => [
                    new ConstraintsFile([
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                            'allowLandScape' => false,
                            'maxWidth' => 600,
                            'maxHeight' => 600

                        ],
                        'mimeTypesMessage' => "Veuillez choisir une image au format PNG ou JPG. Ayant une heuteur et une largeur maximale de 600px",
                    ])
                ]
            ])

            //Methode pour ajouter un fichier audio.
            ->add('upload_audio', FileType::class, [
                'label' => "music",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new ConstraintsFile([
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/wav',
                            'audio/webm',
                            'audio/aac',
                        ],
                        'mimeTypesMessage' => "Merci d'envoyer un fichier audio au format mpeg, wav, webm ou aac."
                    ])
                ]
            ]);
    }

    //Methode pour configurer les options du formulaire.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'is_update' => false,
        ]);
    }
}
