<?php

namespace App\Entity;

use App\Repository\RegleSejourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegleSejourRepository::class)]
class RegleSejour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $check_in = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $check_out = null;

    #[ORM\ManyToMany(targetEntity: Hebergement::class, inversedBy: 'regleSejours')]
    private Collection $hebergements;

    #[ORM\ManyToMany(targetEntity: Saison::class, inversedBy: 'regleSejours')]
    private Collection $saisons;

    public function __construct()
    {
        $this->hebergements = new ArrayCollection();
        $this->saisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckIn(): ?array
    {
        return $this->check_in;
    }

    public function setCheckIn(?array $check_in): static
    {
        $this->check_in = $check_in;

        return $this;
    }

    public function getCheckOut(): ?array
    {
        return $this->check_out;
    }

    public function setCheckOut(?array $check_out): static
    {
        $this->check_out = $check_out;

        return $this;
    }

    /**
     * @return Collection<int, Hebergement>
     */
    public function getHebergements(): Collection
    {
        return $this->hebergements;
    }

    public function addHebergement(Hebergement $hebergement): static
    {
        if (!$this->hebergements->contains($hebergement)) {
            $this->hebergements->add($hebergement);
        }

        return $this;
    }

    public function removeHebergement(Hebergement $hebergement): static
    {
        $this->hebergements->removeElement($hebergement);

        return $this;
    }

    /**
     * @return Collection<int, Saison>
     */
    public function getSaisons(): Collection
    {
        return $this->saisons;
    }


    public function addSaison(Saison $saison): static
    {
        if (!$this->saisons->contains($saison)) {
            $this->saisons->add($saison);
        }

        return $this;
    }

    public function removeSaison(Saison $saison): static
    {
        $this->saisons->removeElement($saison);

        return $this;
    }
}
