<?php

namespace App\Form;

use App\Entity\Recettes;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RecetteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la recette',
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG, GIF, etc.)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image valide',
                    ])
                ],
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => 'Ingrédients',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et préparation',
                'attr' => ['class' => 'form-control', 'rows' => 10]
            ])
            ->add('categories', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Entrée' => 'entree',
                    'Plat principal' => 'plat',
                    'Dessert' => 'dessert',
                    'Boisson' => 'boisson',
                    'Apéritif' => 'aperitif'
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recettes::class,
            'csrf_protection' => true,
        ]);
    }
}