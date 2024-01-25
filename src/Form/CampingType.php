<?php

namespace App\Form;

use App\Entity\Camping;
use App\Form\Type\CustomEmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('telephone')
            ->add('email', CustomEmailType::class) // Use the custom email form type
            ->add('site')
            ->add('code_naf')
            ->add('capital')
            ->add('forme_juridique');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Camping::class,
        ]);
    }
}
