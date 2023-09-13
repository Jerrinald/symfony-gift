<?php

namespace App\Form;

use App\Entity\Gift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GiftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre nom et prénom',
            ])
            ->add('email', TextType::class, [
                'label' => 'Votre email',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du cadeau',
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
            ])
            ->add('urlPurchase', TextType::class, [
                'label' => 'Lien d\'achat',
                'required' => false
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gift::class,
        ]);
    }
}
