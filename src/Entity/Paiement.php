<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $methode = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): static
    {
        $this->methode = $methode;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    // custom
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if ($this->getDate() === null)
            $this->setDate(new DateTime('now'));
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
