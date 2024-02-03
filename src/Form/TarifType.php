<?php

namespace App\Form;

use App\Entity\Tarif;
use App\Form\Type\CustomCheckboxType;
use App\Form\Type\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('par_nuit', CustomCheckboxType::class, ['label' => 'Par nuit'])
            ->add('par_personne', CustomCheckboxType::class, ['label' => 'Par personne'])
            // ->add('adulte')
            // ->add('enfant')
            // ->add('hebergement')
            // ->add('option')
            // ->add('saisons', CustomSaisonType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarif::class,
        ]);
    }
}
