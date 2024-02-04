<?php

namespace App\Form\Type\Field;

use App\Entity\Option;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomDaysType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "choices" => [
                "Tous" => null,
                "Lundi" => "Lundi",
                "Mardi" => "Mardi",
                "Mercredi" => "Mercredi",
                "Jeudi" => "Jeudi",
                "Vendredi" => "Vendredi",
                "Samedi" => "Samedi",
                "Dimanche" => "Dimanche",
            ],
            "multiple" => true,
            'required' => true,
            'placeholder' => 'Tous', // Set the default value to "Tous",
            'constraints' => [new NotBlank]
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
