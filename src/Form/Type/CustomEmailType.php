<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomEmailType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'autocomplete' => 'email',
                'class' => 'block pointer-events-auto px-2.5 pb-2.5 pt-4 mt-2 text-lg text-gray-900 border w-full appearance-none focus:outline-none focus:ring-0 focus:border-main-600 ',
                'placeholder' => 'exemple@exemple.com',
            ],
            'label_attr' => [
                'class' => 'text-xl'
            ],
            'label' => "Adresse email",
        ]);
    }

    public function getParent(): string
    {
        return EmailType::class;
    }
}