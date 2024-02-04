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

class CustomGenreType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "choices" => [
                "Homme" => "homme",
                "Femme" => "femme",
                "Autre" => "autre"
            ],
            'multiple' => false,
            'required' => true,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
