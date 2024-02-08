<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\Type\Entity\CustomEmplacementType;
use App\Form\Type\Entity\CustomHebergementType;
use App\Form\Type\Entity\CustomOptionType;
use App\Form\Type\Field\CustomDateType;
use App\Form\Type\Field\CustomPriceType;
use App\Service\ReservationService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    private ReservationService $reservationService;
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $reservation = $options['data'];
        switch ($options['flow_step']) {
            case 1:
                $builder->add('debut', CustomDateType::class);
                $builder->add('fin', CustomDateType::class);
                $builder->add('adultes', IntegerType::class);
                $builder->add('enfants', IntegerType::class);
                break;
            case 2:
                $hebergementsChoices = $this->reservationService->getHebergementsChoices($reservation);
                $builder->add('hebergement', CustomHebergementType::class, ['choices' => $hebergementsChoices, 'mapped' => true, 'multiple' => false, 'help' => '']);
                break;
            case 3:
                dump($reservation);
                $emplacementsChoices = $this->reservationService->getEmplacementsChoices($reservation);
                $builder->add('emplacement', CustomEmplacementType::class, ['choices' => $emplacementsChoices, 'multiple' => false, 'help' => '']);
        }
    }

    public function getBlockPrefix()
    {
        return 'reservation';
    }
}
