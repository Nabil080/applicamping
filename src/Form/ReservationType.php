<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\Type\Entity\CustomEmplacementType;
use App\Form\Type\Entity\CustomOptionType;
use App\Form\Type\Field\CustomDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        switch ($options['step']) {
            case 0:
                $builder
                ->add('debut', CustomDateType::class)
                ->add('fin', CustomDateType::class)
                ->add('adultes')
                ->add('enfants');
                break;
            
            default:
                # code...
                break;
        }

            // ->add('commentaire')
            // ->add('montant')
            // ->add('statut')
            // ->add('user')
            // ->add('emplacement', CustomEmplacementType::class)
            // ->add('options', CustomOptionType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'step' => 0
        ]);
    }
}
