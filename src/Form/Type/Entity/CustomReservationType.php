<?php

namespace App\Form\Type\Entity;

use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomReservationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'class' => Reservation::class,
                'choice_label' => function (Reservation $reservation) {
                    $user = $reservation->getUser();
                    $emplacement = $reservation->getEmplacement();

                    return "N° ". $reservation->getId() . ", ". $user->getGenre() . $user->getNom() ." ". $user->getPrenom() . ". Emplacement " . $emplacement->getNumero();
                }
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
