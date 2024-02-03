<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Hebergement;
use App\Form\Type\CustomHebergementType;
use App\Form\Type\CustomStatutType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmplacementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('statut', CustomStatutType::class)
            ->add('hebergement', CustomHebergementType::class, ['multiple' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emplacement::class,
        ]);
    }
}
