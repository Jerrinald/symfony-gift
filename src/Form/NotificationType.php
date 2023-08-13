<?php

namespace App\Form;

use App\Entity\Content;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => 'A quel type de personne souhaitez-vous envoyer le SMS ?',
                'mapped' => false,
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
                ],
                'choices'  => [
                    'Madame' => 'Madame',
                    'Monsieur' => 'Monsieur',
                    'Madame, Monsieur' => 'Madame, Monsieur',
                ],
            ])
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
            ->add('interval', ChoiceType::class, [
                'label' => 'Choisir les destinataires cibles ?',
                'mapped' => false,
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
                ],
                'choices'  => [
                    'À partir de' => 'À partir de',
                    'Moins que' => 'Moins que',
                    'Entre' => 'Entre',
                ],
            ])
            ->add('content', EntityType::class, [
                'class' => Content::class,
                'required' => true,
                'mapped' => false,
                'label' => 'Contenu',
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
            ->add('debut', TextType::class, [
                'label' => 'Début',
                'mapped' => false,
                'required' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'placeholder' => '18'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                ],
            ])
            ->add('fin', TextType::class, [
                'label' => 'Fin',
                'mapped' => false,
                'required' => false,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700'
                ],
                'attr' => [
                    'class' => 'mt-1 p-2 block w-full rounded-md border-gray-400 border-[1px] shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'placeholder' => '25'
                ],
            ])
        ;
    }
}
