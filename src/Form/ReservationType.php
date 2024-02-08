<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\Type\Entity\CustomEmplacementType;
use App\Form\Type\Entity\CustomOptionType;
use App\Form\Type\Field\CustomDateType;
use App\Form\Type\Field\CustomPriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['flow_step']) {
            case 1:
                $builder->add('debut', CustomDateType::class);
                $builder->add('fin', CustomDateType::class);
                $builder->add('adultes', IntegerType::class);
                $builder->add('enfants', IntegerType::class);
                break;
            case 2:
                // This form type is not defined in the example.
                $builder->add('montant', CustomPriceType::class);
                break;
        }
    }

    public function getBlockPrefix()
    {
        return 'reservation';
    }
}
