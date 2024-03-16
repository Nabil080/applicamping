<?php

namespace App\Form;

use App\Entity\Camping;
use App\Form\Type\Field\CustomEmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CampingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [new NotBlank]
            ])
            ->add('adresse')
            ->add('telephone')
            ->add('email', CustomEmailType::class, [
                'constraints' => []
            ])
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
