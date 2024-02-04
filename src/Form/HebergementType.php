<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Form\Type\CustomStatutType;
use App\Form\Type\Field\CustomImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('image', CustomImageType::class, [])
            ->add('minimum')
            ->add('maximum')
            ->add('statut', CustomStatutType::class )
        ;
    }

// todo : STATUTtype IMAGEtype

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}
