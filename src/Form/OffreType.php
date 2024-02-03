<?php

namespace App\Form;

use App\Entity\Offre;
use App\Form\Type\CustomDateType;
use App\Form\Type\CustomDeviseType;
use App\Form\Type\CustomHebergementType;
use App\Form\Type\CustomPriceType;
use App\Form\Type\CustomStatutType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => ["Remise" => "remise", "Coupon" => "coupon"]
            ])
            ->add('nom')
            ->add('montant', CustomPriceType::class)
            ->add('devise', CustomDeviseType::class)
            ->add('debut', CustomDateType::class, ['required' => false, 'help' => 'Date de début, peut être vide'])
            ->add('fin', CustomDateType::class, ['required' => false, 'help' => 'Date de fin, peut être vide'])
            ->add('utilisations', IntegerType::class, ['required' => false, 'help' => 'Limite d\'utilisation, peut être vide'])
            ->add('hebergements', CustomHebergementType::class)
            ->add('statut', CustomStatutType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
