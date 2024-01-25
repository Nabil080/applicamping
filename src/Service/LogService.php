<?php

namespace App\Service;

use App\Entity\Log;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class LogService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function write(string $message, string $context, string $type): void
    {
        $log = new Log;
        $user = $this->security->getUser();
        $log->setUser($user);

        $log->setMessage($message);
        $log->setContexte($context);
        $log->setType($type);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function translate($string): string
    {
        return match ($string) {
            default => '',
            'places' => 'emplacement',
            'offers' => 'offre',
            'locations' => 'emplacement',
            'accomodations' => 'hébérgement'
        };
    }
}
