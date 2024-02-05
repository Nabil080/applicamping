<?php

namespace App\Service;

use App\Entity\Emplacement;
use App\Entity\Hebergement;
use App\Entity\Log;
use App\Repository\ReservationRepository;
use App\Repository\EmplacementRepository;
use App\Repository\HebergementRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReservationService
{
    private ReservationRepository $reservationRepository;
    private EmplacementRepository $emplacementRepository;
    private HebergementRepository $hebergementRepository;

    public function __construct(ReservationRepository $reservationRepository, EmplacementRepository $emplacementRepository, HebergementRepository $hebergementRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->emplacementRepository = $emplacementRepository;
        $this->hebergementRepository = $hebergementRepository;
    }

    public function getHebergementsByRequest(Request $request): array
    {
        // ? Récupère les données de la requête
        $start = date_create_from_format('d/m/Y',$request->query->get('start')) ;
        $end = date_create_from_format('d/m/Y',$request->query->get('end')) ;
        $adult = $request->query->get('adult');
        $child = $request->query->get('child');

        // ? Récupère la saison de la période




        // ? Récupère les hébèrgements autorisés
        // Règle d'arrivés / de départ
        // Règles de durée minimum / maximum
        // Statut actif


        // Associe à chaque hébérgement ses emplacements 
        $hebergements = $this->hebergementRepository->findBy(['statut' => 'Actif']);
        $hebergementsArray = array_map(
            fn (Hebergement $hebergement) => [
                "hebergement" => $hebergement,
                "emplacements" => $hebergement->getEmplacements()->filter(fn (Emplacement $emplacement) => $emplacement->getStatut() == 'Actif')
            ],
            $hebergements
        );
        // pour chaque hébérgement, garde uniquement les emplacements libres pour la période donnée




        // ne renvoie que les hébergements avec au moins un emplacement
        return $hebergementsArray;


        // $emplacements = $this->emplacementRepository->findBy(['statut' => 'Actif']);
        // $libres = array_filter($emplacements, function (Emplacement $emplacement) use ($start, $end) {
        //     return $emplacement->isAvailable();
        // });

        // return $libres;
    }
}
