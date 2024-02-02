<?php

namespace App\Form;

use App\Entity\Saison;
use App\Entity\SaisonDate;
use App\Form\Type\CustomDateType;
use App\Form\Type\CustomSaisonType;
use App\Form\Type\CustomDaysType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaisonDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('debut', CustomDateType::class)
            ->add('fin', CustomDateType::class)
            ->add('jours', CustomDaysType::class)
            ->add('saison', CustomSaisonType::class, [
                'label' => "Saison", 
                'multiple' => false,
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaisonDate::class,
        ]);
    }
}
