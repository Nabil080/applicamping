<?php

namespace App\Form;

use App\Entity\OptionMaximum;
use App\Form\Type\CustomHebergementType;
use App\Form\Type\CustomOptionType;
use App\Form\Type\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OptionMaximumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nombre', NumberType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank
            ]
        ])
            ->add('option', CustomOptionType::class)
            ->add('hebergements', CustomHebergementType::class)
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
