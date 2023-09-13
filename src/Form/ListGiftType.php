<?php

namespace App\Form;

use App\Entity\ListGift;
use App\Entity\Gift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ListGiftType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();
        
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('theme', TextType::class, [
                'label' => 'Thème',
            ])

            ->add('description')
            ->add('isPrivate', CheckboxType::class, [
                'label' => 'Privée ',
            ])

            /*->add('gift', null, [
                'query_builder' => function (EntityRepository $er) use ($currentUser) {
                    return $er->createQueryBuilder('g')
                        ->andWhere('g.userGift = :user')
                        ->setParameter('user', $currentUser);
                },
            ])*/
            ->add('openingDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => ['class' => 'datepicker'],
                'label' => 'Date d\'ouverture',
            ])
            ->add('closingDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'data' => (new \DateTime())->modify('+1 day'),
                'attr' => ['class' => 'datepicker'],
                'label' => 'Date de fermeture',
            ])
            
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de couverture',
                'required' => false
            ])

            ->add('password', PasswordType::class, [
                'attr' => ['type' => 'password'], // Indicate that this is an input type password
                'label' => 'Mot de passe',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListGift::class,
        ]);
    }
}
