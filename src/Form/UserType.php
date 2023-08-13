<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => User::TYPES,
                'label' => 'Type utilisateur',
                'required' => true,
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
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'autocomplete' => 'email',
                    'placeholder' => 'john.doe@wecan.fr'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Email([
                        'message' => "Merci de choisir un email valide !"
                    ])
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'autocomplete' => 'given-name',
                    'placeholder' => '+33604444761'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre numéro doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre numéro doit faire moins de {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'autocomplete' => 'last-name',
                    'placeholder' => 'DOE'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre nom doit faire moins de {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'autocomplete' => 'given-name',
                    'placeholder' => 'John'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre prénom doit faire moins de {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'autocomplete' => 'given-name',
                    'placeholder' => '+33768462492'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre prénom doit faire moins de {{ limit }} caractères',
                        'max' => 13,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => User::ROLES,
                'label' => 'Rôles',
                'multiple' => true,
                'placeholder' => 'Sélection du rôles',
                'required' => true,
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
            ->add('gender', ChoiceType::class, [
                'choices' => User::GENDER,
                'label' => 'Genre',
                'placeholder' => 'Sélection du genre',
                'required' => true,
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
            ->add('born', BirthdayType::class, [
                'widget' => 'choice',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'Europe/Paris',
                'view_timezone' => 'Europe/Paris',
                'label' => "Date de naissance",
                'data' => new \DateTime(),
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'placeholder' => '01/01/1990'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                ],
            ])
            ->add('job_integer', EntityType::class, [
                'class' => Job::class,
                'required' => true,
                'label' => 'Métier',
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
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'user_item',
        ]);
    }
}
