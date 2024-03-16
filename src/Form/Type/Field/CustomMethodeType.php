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

class CustomMethodeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "choices" => [
                "Carte bancaire" => "Carte bancaire",
                "Virement bancaire" => "Virement bancaire",
                "Chèque vacance" => "Chèque vacance",
                "Chèque" => "Chèque",
                "Espèce" => "Espèce",
                "Acompte" => "Acompte",
                "Remise" => "Remise",
                "Coupon" => "Coupon",
                "Réduction administrateur" => "Réduction administrateur",
                "Remboursement" => "Remboursement",
                "Taxe de séjour" => "Taxe de séjour",
            ],
            "multiple" => false,
            'required' => true,
            'constraints' => [new NotBlank]
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
