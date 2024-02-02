<?php

namespace App\Form\Type;

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
                "Tous" => "null",
                "Lundi" => "1",
                "Mardi" => "2",
                "Mercredi" => "3",
                "Jeudi" => "4",
                "Vendredi" => "5",
                "Samedi" => "6",
                "Dimanche" => "7",
            ]
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
