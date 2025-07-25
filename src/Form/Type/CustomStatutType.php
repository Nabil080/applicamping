<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomStatutType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'empty_data' => 'Actif',
            'empty_value' => 'Actif',
            'required' => false,
            'attr' => [
                'placeholder' => 'Actif',
            ],
            'constraints' => [
                new NotBlank,
            ]
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
