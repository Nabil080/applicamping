<?php

namespace App\Form;

use App\Entity\Tarif;
use App\Form\Type\CustomCheckboxType;
use App\Form\Type\CustomHebergementType;
use App\Form\Type\CustomOptionType;
use App\Form\Type\CustomPriceType;
use App\Form\Type\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', CustomPriceType::class)
            ->add('par_nuit', CustomCheckboxType::class, ['label' => 'Par nuit'])
            ->add('par_personne', CustomCheckboxType::class, ['label' => 'Par personne'])
            // ->add('adulte', CustomCheckboxType::class, ['label' => 'adulte'])
            // ->add('enfant', CustomCheckboxType::class, ['label' => 'enfant'])
            // ->add('hebergement', CustomHebergementType::class, ['multiple' => false, 'required' => false])
            // ->add('option', CustomOptionType::class, ['multiple' => false, 'required' => false])
            ->add('saisons', CustomSaisonType::class)
        ;

        // dd('hi');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarif::class,
        ]);
    }
}
