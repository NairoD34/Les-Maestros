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
class AdminProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            -> add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'constraints' => [
                    new ConstraintsFile([
                        'mimeTypes' => [ // We want to let upload only image
                            'image/jpg',
                            'image/png',
                            'image/jpeg',

                        ],
                        'mimeTypesMessage' => "This document isn't valid.",
                    ])
                ]
            ])
            -> add('upload_audio', FileType::class, [
                'label' => "music",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new ConstraintsFile([ //Nothing else than audio should be uploaded here
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/wav',
                            'audio/webm',
                            'audio/aac',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid audio file.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
