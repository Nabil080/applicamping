<?php

namespace App\Form;

use App\Entity\RegleReservation;
use App\Form\Type\CustomCheckboxType;
use App\Form\Type\CustomPriceType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegleReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client_nom', CustomCheckboxType::class, ['label' => 'Nom'])
            ->add('client_prenom', CustomCheckboxType::class, ['label' => 'Prénom'])
            ->add('client_email', CustomCheckboxType::class, ['label' => 'Email'])
            ->add('client_telephone', CustomCheckboxType::class, ['label' => 'Téléphone'])
            ->add('client_adresse', CustomCheckboxType::class, ['label' => 'Adresse'])
            ->add('emplacement_libre', CustomCheckboxType::class, ['label' => 'Libre', 'help' => 'Choix via carte interactive'])
            ->add('emplacement_aleatoire', CustomCheckboxType::class, ['label' => 'Aléatoire'])
            ->add('emplacement_defini', CustomCheckboxType::class, ['label' => 'Défini'])
            ->add('paiement_carte_bancaire', CustomCheckboxType::class, ['label' => 'Carte bancaire'])
            ->add('paiement_virement_bancaire', CustomCheckboxType::class, ['label' => 'Virement bancaire'])
            ->add('paiement_cheque_vacance', CustomCheckboxType::class, ['label' => 'Cheque vacance'])
            ->add('paiement_cheque', CustomCheckboxType::class, ['label' => 'Cheque'])
            ->add('paiement_espece', CustomCheckboxType::class, ['label' => 'Espèce'])
            ->add('acompte', CustomCheckboxType::class, ['label' => 'Acompte', 'help' => 'Obligation d\'acompte'])
            ->add('paiement_sur_place', CustomCheckboxType::class, ['label' => 'Sur place', 'help' => ''])
            ->add('acompte_montant', CustomPriceType::class, ['label' => 'Acompte', 'help' => 'en % et virgules autorisés.'] )
            ->add('taxe_sejour_montant', CustomPriceType::class, ['label' => 'Taxe de séjour', 'help' => 'en € et virgules autorisés.'] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegleReservation::class,
        ]);
    }
}
