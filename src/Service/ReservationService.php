<?php

namespace App\Service;

use App\Entity\Emplacement;
use App\Entity\Hebergement;
use App\Entity\Log;
use App\Entity\RegleDuree;
use App\Entity\Saison;
use App\Entity\Tarif;
use App\Repository\ReservationRepository;
use App\Repository\EmplacementRepository;
use App\Repository\HebergementRepository;
use App\Repository\PeriodeRepository;
use App\Repository\RegleDureeRepository;
use App\Repository\RegleSejourRepository;
use App\Repository\SaisonRepository;
use App\Repository\TarifRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReservationService extends AbstractController
{
    private ReservationRepository $reservationRepository;
    private EmplacementRepository $emplacementRepository;
    private HebergementRepository $hebergementRepository;
    private SaisonRepository $saisonRepository;
    private PeriodeRepository $periodeRepository;
    private RegleDureeRepository $regleDureeRepository;
    private RegleSejourRepository $regleSejourRepository;
    private TarifRepository $tarifRepository;

    public function __construct(TarifRepository $tarifRepository, RegleSejourRepository $regleSejourRepository, RegleDureeRepository $regleDureeRepository, PeriodeRepository $periodeRepository, ReservationRepository $reservationRepository, EmplacementRepository $emplacementRepository, HebergementRepository $hebergementRepository, SaisonRepository $saisonRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->emplacementRepository = $emplacementRepository;
        $this->hebergementRepository = $hebergementRepository;
        $this->saisonRepository = $saisonRepository;
        $this->periodeRepository = $periodeRepository;
        $this->regleDureeRepository = $regleDureeRepository;
        $this->regleSejourRepository = $regleSejourRepository;
        $this->tarifRepository = $tarifRepository;
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

        $displayHebergements = [];
        foreach ($hebergements as $hebergement) {
            $displayHebergement = new DisplayHebergement($hebergement, $saison, $adult, $child, $start, $end);
            // ? Vérifie les règles et ajoute une erreur ou non
            $this->checkErrors($displayHebergement);
            // ? Récupère le tarif correspondant
            $this->getTarif($displayHebergement);
            // ? Récupère les émplacements correspondants
            $this->getEmplacements($displayHebergement);
            $displayHebergements[] = $displayHebergement;
        };

        return ($displayHebergements);
    }

    // fonctions principales

    public function checkErrors(DisplayHebergement $displayHebergement): void
    {
        // Règle de nombre de personnes
        $this->checkSize($displayHebergement);
        // Règles de durée minimum / maximum
        $this->checkLength($displayHebergement, 'minimum');
        $this->checkLength($displayHebergement, 'maximum');
        // Règle d'arrivés / de départ
        $this->checkDays($displayHebergement, 'checkin');
        $this->checkDays($displayHebergement, 'checkout');
        // Statut actif
        $this->checkStatut($displayHebergement);
    }

    public function getTarif(DisplayHebergement $displayHebergement): void
    {
        $tarifs = $displayHebergement->hebergement->getTarifs()->getValues();

        foreach ($tarifs as $tarif) {
            // PRIO 1 : Saison correspondante
            if ($tarif->getSaisons()->contains($displayHebergement->saison))
                $tarif1 = $tarif;
            // PRIO 2 : Toute saison
            if ($tarif->getSaisons()->count() === 0)
                $tarif2 = $tarif;
        }

        if($tarifs === []) $displayHebergement->error[] = "Aucun tarif indiqué";
        else $displayHebergement->setTarif($tarif1 ?? $tarif2);
    }

    public function getEmplacements(DisplayHebergement $displayHebergement): void
    {
        // Récupère les emplacements de l'hébergement
        $emplacements = $displayHebergement->hebergement->getEmplacements()->filter(fn(Emplacement $emplacement)=>$emplacement->getStatut() === "Actif");
        $displayHebergement->emplacements = ["Libres" => [], "Occupés" => [], "Total" => count($emplacements)];
        
        // Sépare les emplacements libres et occupés pour les dates donnés
        foreach($emplacements as $emplacement){
            $statut = $emplacement->isOccupied($displayHebergement->start,$displayHebergement->end) ? "Occupés" : "Libres";
            $displayHebergement->emplacements[$statut][] = $emplacement;
        }
    }

    // Sous fonctions
    public function checkSize(DisplayHebergement $displayHebergement): void
    {
        $size = $displayHebergement->adult + $displayHebergement->child;
        $minimum = $displayHebergement->hebergement->getMinimum();
        $maximum = $displayHebergement->hebergement->getMaximum();

        if ($size < $minimum) $displayHebergement->error[] = "Trop peu de personnes, minimum requis : " . $minimum;
        if ($size > $maximum) $displayHebergement->error[] = "Trop de personnes, maximum autorisé : " . $maximum;
    }

    public function checkLength(DisplayHebergement $displayHebergement, string $type = 'minimum'): void
    {
        $length = $displayHebergement->start->diff($displayHebergement->end)->format('%a%');
        $lengthRules = $type === 'minimum' ? $this->regleDureeRepository->getMinStay() : $this->regleDureeRepository->getMaxStay();

        foreach ($lengthRules as $rule) {
            // PRIO 1 : Hébérgement + saison correspondante
            if ($rule->getHebergements()->contains($displayHebergement->hebergement) && $rule->getSaisons()->contains($displayHebergement->saison))
                $rule1 = $rule;
            // PRIO 2 : Hébérgement + toute saison
            if ($rule->getHebergements()->contains($displayHebergement->hebergement) && $rule->getSaisons()->count() === 0)
                $rule2 = $rule;
            // PRIO 3 : Tout hébérgement + saison correspondante
            if ($rule->getHebergements()->count() === 0 && $rule->getSaisons()->contains($displayHebergement->saison))
                $rule3 = $rule;
            // PRIO 4 : Tout hébérgement + toute saison
            if ($rule->getHebergements()->count() === 0 && $rule->getSaisons()->count() === 0)
                $rule4 = $rule;
        }

        // Utilise la règle la plus précise
        $lengthRule = $rule1 ?? $rule2 ?? $rule3 ?? $rule4;
        // Vérifie avec la durée de la réservation
        if ($lengthRule->getMinimum() && $length < $lengthRule->getMinimum())
            $displayHebergement->error[] = "Le séjour est trop court, nuits minimum requises : " . $lengthRule->getMinimum();
        if ($lengthRule->getMaximum() && $length < $lengthRule->getMinimum())
            $displayHebergement->error[] = "Le séjour est trop long, nuits maximum autorisés : " . $lengthRule->getMaximum();
    }

    public function checkDays(DisplayHebergement $displayHebergement, string $type = 'checkin'): void
    {
        $startDay = $displayHebergement->start->format('N');
        $endDay = $displayHebergement->end->format('N');
        $dayRules = $type === 'checkin' ? $this->regleSejourRepository->getCheckIns() : $this->regleSejourRepository->getCheckOuts();

        foreach ($dayRules as $rule) {
            // PRIO 1 : Hébérgement + saison correspondante
            if ($rule->getHebergements()->contains($displayHebergement->hebergement) && $rule->getSaisons()->contains($displayHebergement->saison))
                $rule1 = $rule;
            // PRIO 2 : Hébérgement + toute saison
            if ($rule->getHebergements()->contains($displayHebergement->hebergement) && $rule->getSaisons()->count() === 0)
                $rule2 = $rule;
            // PRIO 3 : Tout hébérgement + saison correspondante
            if ($rule->getHebergements()->count() === 0 && $rule->getSaisons()->contains($displayHebergement->saison))
                $rule3 = $rule;
            // PRIO 4 : Tout hébérgement + toute saison
            if ($rule->getHebergements()->count() === 0 && $rule->getSaisons()->count() === 0)
                $rule4 = $rule;
        }

        // Utilise la règle la plus précise
        $dayRule = $rule1 ?? $rule2 ?? $rule3 ?? $rule4;
        // Vérifie avec le jour d'arrivé / départ
        $authorizedDays = $dayRule->getFormattedDays($type);
        // 0 = Tous, 1 = Lundi ... 7 = Dimanche
        if ($type === 'checkin' && !(in_array($startDay, $authorizedDays) | in_array(0, $authorizedDays)))
            $displayHebergement->error[] = "Le jour d\'arrivé n'est pas autorisé, sont autorisés : " . implode(', ', $dayRule->getCheckIn());
        if ($type === 'checkout' && !(in_array($endDay, $authorizedDays) | in_array(0, $authorizedDays)))
            $displayHebergement->error[] = "Le jour de départ n'est pas autorisé, sont autorisés : " . implode(', ', $dayRule->getCheckOut());
    }

    public function checkStatut(DisplayHebergement $displayHebergement): void
    {
        if ($displayHebergement->hebergement->getStatut() === 'Maintenance') $displayHebergement->error[] = "Cet hebergement est en cours de maintenance";
    }
}


class DisplayHebergement
{
    public Hebergement $hebergement;
    public Saison $saison;
    public DateTime $start;
    public DateTime $end;
    public int $adult = 0;
    public int $child = 0;
    public array $emplacements = [];
    public Tarif $tarif;
    public array $error = [];


    public function __construct(Hebergement $hebergement, Saison $saison, int $adult, int $child, DateTime $start, DateTime $end)
    {
        $this->hebergement = $hebergement;
        $this->saison = $saison;
        $this->adult = $adult;
        $this->child = $child;
        $this->start = $start;
        $this->end = $end;
    }

    public function setTarif(Tarif $tarif): void
    {
        $this->tarif = $tarif;
    }
}
