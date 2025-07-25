<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Type\CustomEmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // obligatoires
            ->add('email', CustomEmailType::class)
            ->add('nom', TextType::class, [
                'attr' => [
                    'autocomplete' => 'family-name',
                    'class' => 'block pointer-events-auto px-2.5 pb-2.5 pt-4 mt-2 text-lg text-gray-900 border w-full appearance-none focus:outline-none focus:ring-0 focus:border-main-600 ',
                ],
                'label_attr' => [
                    'class' => 'text-xl'
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'autocomplete' => 'given-name',
                    'class' => 'block pointer-events-auto px-2.5 pb-2.5 pt-4 mt-2 text-lg text-gray-900 border w-full appearance-none focus:outline-none focus:ring-0 focus:border-main-600 ',
                ],
                'label_attr' => [
                    'class' => 'text-xl'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => "Mot de passe",
                'label_attr' => [
                    'class' => 'text-xl'
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'block pointer-events-auto px-2.5 pb-2.5 pt-4 mt-2 text-lg text-gray-900 border w-full appearance-none focus:outline-none focus:ring-0 focus:border-main-600 ',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
