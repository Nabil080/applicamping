<?php

namespace App\Service;

use App\Entity\Log;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\SecurityBundle\Security;

class LogService
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private Log $log;
    private Object $entity;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function write(Object $entity, string $type): void
    {
        $this->log = new Log;
        $this->entity = $entity;

        $user = $this->security->getUser();
        $this->log->setUser($user);

        $contexte = $this->getEntityName();
        $this->log->setContexte($contexte);

        $this->log->setType($type);

        $message = $this->getMessage();
        $this->log->setMessage($message);

        $this->entityManager->persist($this->log);
        $this->entityManager->flush();
    }

    public function getMessage(): string
    {
        $entityString = match ($this->getEntityName()) {
            "Tag" => 'Le tag "' . $this->entity->getNom() . '" ',
            "Hebergement" => 'L\'hébergement "' . $this->entity->getNom() . '" ',
            "Emplacement" => 'L\'emplacement "' . $this->entity->getNumero() . '" ',
            "Camping" => 'Le camping  "' . $this->entity->getNom() . '" ',
            "Option" => 'Une option supplémentaire  "' . $this->entity->getNom() . '" ',
            "OptionMaximum" => 'Un maximum de "' . $this->entity->getNombre() . '" pour l\'option "' . $this->entity->getOption()->getNom() . '" ',
            "Saison" => 'La saison "' . $this->entity->getNom() . '" ',
            "Periode" => 'Une période pour la saison "' . $this->entity->getSaison()->getNom() . '" ',
            "Tarif" => 'Un tarif pour "' . ($this->entity->getHebergement()?->getNom()) . ($this->entity->getOption()?->getNom()) . ($this->entity->isAdulte() ? "adulte" : "") . ($this->entity->isEnfant() ? "enfant" : "") . '" ',
            "Offre" => 'Une offre "' . $this->entity->getNom() . '" ',
            "RegleSejour" => 'Une regle ' . ($this->entity->getCheckIn() ? "d'arrivé " : "de départ "),
            "RegleDuree" => 'Une regle de durée ' . ($this->entity->getMinimum() ? "minimum " : "maximum "),
            "RegleReservation" => 'Les règles de réservation du camping ont été modifiées.',
            "User" => 'Un utilisateur "' . $this->entity->getNom() . '" ',
            "Reservation" => 'Une réservation '
        };

        $typeString = match ($this->log->getType()) {
            "create" => "a été crée. ",
            "update" => "a été modifié. ",
            "delete" => "a été supprimé. ",
            "archive" => "a été archivé. ",
        };

        $idString = "(ID° " . $this->entity->getId() . ")";

        switch ($this->getEntityName()) {
            case 'RegleReservation':
                $message = $entityString;
                break;

            default:
                $message = $entityString . $typeString . $idString;
                break;
        }

        return $message;
    }

    public function getEntityName(): string
    {
        $class = get_class($this->entity);
        $classArray = explode("\\", $class);
        $classname = end($classArray);

        return $classname;
    }
}
