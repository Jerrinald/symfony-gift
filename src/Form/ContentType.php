<?php

namespace App\Form;

use App\Entity\Content;
use App\Entity\Type;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'placeholder' => 'Prévention douleurs de dos'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom doit faire moins de {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('image_source', DropzoneType::class, [
                'attr' => [
                    'placeholder' => "Drag and drop l'image du produit",
                ],
                'mapped' => false,
                'required' => true,
                'constraints' =>
                    new File([
                        'maxSize' => '10m',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPG ou PNG',
                    ])
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre description doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre description doit faire moins de {{ limit }} caractères',
                        'max' => 10000,
                    ]),
                ],
            ])
            ->add('type_integer', EntityType::class, [
                'class' => Type::class,
                'required' => true,
                'label' => 'Type',
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] bg-white shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
