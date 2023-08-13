<?php

namespace App\Form;

use App\Entity\Pathology;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class HospitalizationFrontType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entry_date', DateType::class, [
                'widget' => 'single_text',
                'model_timezone' => 'Europe/Paris',
                'view_timezone' => 'Europe/Paris',
                'label' => "Date d'entrée",
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
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'model_timezone' => 'Europe/Paris',
                'view_timezone' => 'Europe/Paris',
                'label' => "Date de sortie",
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
            ->add('pathology_integer', EntityType::class, [
                'class'=> Pathology::class ,
                'label' => 'Nom',
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
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
