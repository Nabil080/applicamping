<?php

namespace App\Form;

use App\Entity\Paiement;
use App\Form\Type\CustomStatutType;
use App\Form\Type\Entity\CustomReservationType;
use App\Form\Type\Field\CustomDateType;
use App\Form\Type\Field\CustomMethodeType;
use App\Form\Type\Field\CustomPriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reservation', CustomReservationType::class)
            ->add('montant', CustomPriceType::class)
            ->add('methode', CustomMethodeType::class)
            ->add('statut', CustomStatutType::class, ['empty_data' => 'Validé']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
