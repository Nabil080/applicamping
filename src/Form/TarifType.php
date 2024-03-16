<?php

namespace App\Form;

use App\Entity\Tarif;
use App\Form\Type\Field\CustomCheckboxType;
use App\Form\Type\Entity\CustomHebergementType;
use App\Form\Type\Entity\CustomOptionType;
use App\Form\Type\Field\CustomPriceType;
use App\Form\Type\Entity\CustomSaisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', CustomPriceType::class)
            ->add('par_nuit', CustomCheckboxType::class, ['label' => 'Par nuit', 'attr' => ['class' => "my-4 caca"]])
            ->add('par_personne', CustomCheckboxType::class, ['label' => 'Par personne', 'attr' => ['class' => "my-4 caca"]]);
        
        switch ($options['type']) {
            case 'hebergement':
                $builder->add('hebergement', CustomHebergementType::class, ['multiple' => false, 'required' => false]);
                break;
            case 'option':
                $builder->add('option', CustomOptionType::class, ['multiple' => false, 'required' => false]);
                break;
            case 'ageCategory':
                $builder
                    ->add('adulte', CustomCheckboxType::class, ['label' => 'Adulte'])
                    ->add('enfant', CustomCheckboxType::class, ['label' => 'Enfant']);
                break;
        }

        $builder->add('saisons', CustomSaisonType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarif::class,
            'type' => '',
        ]);
    }
}
