<?php

namespace App\Form\Type;

use App\Entity\Hebergement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomHebergementType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'class' => Hebergement::class,
                'choice_label' => 'nom',
                'label' => 'Hébergements (Laisser vide pour tous)',
                'multiple' => true,
                'required' => false
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
