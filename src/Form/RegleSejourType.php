<?php

namespace App\Form;

use App\Entity\RegleSejour;
use App\Form\Type\CustomDaysType;
use App\Form\Type\CustomHebergementType;
use App\Form\Type\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegleSejourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        switch ($options['type']) {
            case 'checkin':
                $builder->add('check_in', CustomDaysType::class);
                break;
            case 'checkout':
                $builder->add('check_out', CustomDaysType::class);
                break;
            default:
                $builder->add('check_in', CustomDaysType::class)->add('check_out', CustomDaysType::class);
                break;
        }

        $builder
            ->add('hebergements', CustomHebergementType::class)
            ->add('saisons', CustomSaisonType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegleSejour::class,
            'type' => ''
        ]);
    }
}
