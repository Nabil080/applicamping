<?php

namespace App\Service;

use App\Entity\Emplacement;
use App\Entity\Hebergement;
use App\Entity\Log;
use App\Entity\RegleDuree;
use App\Entity\Saison;
use App\Repository\ReservationRepository;
use App\Repository\EmplacementRepository;
use App\Repository\HebergementRepository;
use App\Repository\PeriodeRepository;
use App\Repository\SaisonRepository;
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
    private SaisonRepository $saisonRepository;
    private PeriodeRepository $periodeRepository;

    public function __construct(PeriodeRepository $periodeRepository, ReservationRepository $reservationRepository, EmplacementRepository $emplacementRepository, HebergementRepository $hebergementRepository, SaisonRepository $saisonRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->emplacementRepository = $emplacementRepository;
        $this->hebergementRepository = $hebergementRepository;
        $this->saisonRepository = $saisonRepository;
        $this->periodeRepository = $periodeRepository;
    }

    public function getHebergementsByRequest(Request $request): array
    {
        // ? Récupère les données de la requête
        $start = date_create_from_format('d/m/Y', $request->query->get('start'));
        $end = date_create_from_format('d/m/Y', $request->query->get('end'));
        $adult = $request->query->get('adult');
        $child = $request->query->get('child');

        // ? Récupère la saison de la période
        $saison = $this->periodeRepository->findByStartEnd($start, $end)->getSaison();

        // ? Récupère la liste des hébérgements et créer un DisplayHebergement pour chaque
        $hebergements = $this->hebergementRepository->findBy(['statut' => ['Actif', 'Maintenance'],]);

        $displayHebergements = array_map(
            function ($hebergement) use ($saison, $adult, $child, $start, $end) {
                $displayHebergement = new DisplayHebergement($hebergement, $saison, $adult, $child, $start, $end);
                // ? Vérifie les règles et ajoute une erreur ou non
                return $displayHebergement->checkErrors();
            },
            $hebergements
        );

        return ($displayHebergements);

        // Associe à chaque hébérgement ses emplacements 
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


class DisplayHebergement
{
    private Hebergement $hebergement;
    private Saison $saison;
    private DateTime $start;
    private DateTime $end;
    private int $adult = 0;
    private int $child = 0;
    private array $emplacements = [];
    private int $tarif = 0;
    private array $error = [];
    

    public function __construct(Hebergement $hebergement, Saison $saison, int $adult, int $child, DateTime $start, DateTime $end)
    {
        $this->hebergement = $hebergement;
        $this->saison = $saison;
        $this->adult = $adult;
        $this->child = $child;
        $this->start = $start;
        $this->end = $end;
    }

    public function checkErrors(): DisplayHebergement
    {
        // Règle de nombre de personnes
        $this->checkSize();
        // Règle d'arrivés / de départ
        // $this->checkDays('checkin');
        // $this->checkDays('checkout');
        // // Règles de durée minimum / maximum
        $this->checkLength();
        // $this->checkLength('maximum');
        // // Statut actif
        // $this->checkStatut();

        return $this;
    }

    public function checkSize(): void
    {
        $size = $this->adult + $this->child;
        $minimum = $this->hebergement->getMinimum();
        $maximum = $this->hebergement->getMaximum();

        if ($size < $minimum) $this->error[] = "Trop peu de personnes, minimum requis : " . $minimum;
        if ($size > $maximum) $this->error[] = "Trop de personnes, maximum autorisé : " . $maximum;
    }

    public function checkLength(): void
    {
        $length = $this->start->diff($this->end)->format('%a%');

        // ! Règles de durées ciblant l'hébergement
        $hebergementRulesCollection = $this->hebergement->getRegleDurees();

        // Récupère les règles de durée de l'hébergement et de la saison
        $rules1 = array_map(function (RegleDuree $rule) {
            $rule->getSaisons()->contains($this->saison);
        }, $hebergementRulesCollection->toArray());
        // Récupère les règles de durée de l'hébergement de toutes saisons
        $rules2 = array_map(function (RegleDuree $rule) {
            $rule->getSaisons()->count() === 0;
        }, $hebergementRulesCollection->toArray());

        // ! Règles de durées ciblant la saison
        $saisonRulesCollection = $this->saison->getRegleDurees();

        // Récupère les règles de durée de tout hébergement de la saison
        $rules3 = array_map(function (RegleDuree $rule) {
            $rule->getHebergements()->count() === 0;
        }, $saisonRulesCollection->toArray());

        // Récupère les règles de durée de tout hébergement de toutes saisons
        $rules4 = 


        /*
        Cible hebergement + saison
        Cible hebergement + tout
        Cible tout + saison
        Cible tout + tout
        */

        var_dump($hebergementRulesCollection, $saisonRulesCollection);
    }
}
