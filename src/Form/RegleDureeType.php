<?php

namespace App\Form;

use App\Entity\RegleDuree;
use App\Form\Type\Entity\CustomHebergementType;
use App\Form\Type\Entity\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegleDureeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        switch ($options['type']) {
            case 'minstay':
                $builder->add('minimum', IntegerType::class);
                break;
            case 'maxstay':
                $builder->add('maximum', IntegerType::class);
                break;
            default:
                $builder->add('minimum', IntegerType::class)->add('maximum', IntegerType::class);
                break;
        }

        $builder
            ->add('hebergements', CustomHebergementType::class)
            ->add('saisons', CustomSaisonType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegleDuree::class,
            'type' => ''
        ]);
    }
}
