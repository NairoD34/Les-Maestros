<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

class AdminCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 3,
                    'cols' => 50,
                    'placeholder' => 'Saisissez votre description ici...',
                ],
            ])
            ->add('parent_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => '',
                'empty_data' => null,
            ])
            ->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new ConstraintsFile([
                        'mimeTypes' => [
                            'img/jpg',
                            'img/png',
                            'img/jpeg',

                        ],
                        'mimeTypesMessage' => "This document isn't valid.",
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
