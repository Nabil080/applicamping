<?php

namespace App\Form;

use App\Entity\OptionMaximum;
use App\Form\Type\CustomEmplacementType;
use App\Form\Type\CustomOptionType;
use App\Form\Type\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionMaximumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('option', CustomOptionType::class)
            ->add('emplacements', CustomEmplacementType::class)
            ->add('saisons', CustomSaisonType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OptionMaximum::class,
        ]);
    }
}
