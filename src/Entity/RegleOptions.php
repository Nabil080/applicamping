<?php

namespace App\Entity;

use App\Repository\RegleOptionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegleOptionsRepository::class)]
class RegleOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $mmaximum = null;

    #[ORM\ManyToMany(targetEntity: saison::class, inversedBy: 'regleOptions')]
    private Collection $saisons;

    #[ORM\ManyToMany(targetEntity: hebergement::class, inversedBy: 'regleOptions')]
    private Collection $hebergements;

    public function __construct()
    {
        $this->saisons = new ArrayCollection();
        $this->hebergements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMmaximum(): ?int
    {
        return $this->mmaximum;
    }

    public function setMmaximum(int $mmaximum): static
    {
        $this->mmaximum = $mmaximum;

        return $this;
    }

    /**
     * @return Collection<int, saison>
     */
    public function getSaisons(): Collection
    {
        return $this->saisons;
    }

    public function addSaison(saison $saison): static
    {
        if (!$this->saisons->contains($saison)) {
            $this->saisons->add($saison);
        }

        return $this;
    }

    public function removeSaison(saison $saison): static
    {
        $this->saisons->removeElement($saison);

        return $this;
    }

    /**
     * @return Collection<int, hebergement>
     */
    public function getHebergements(): Collection
    {
        return $this->hebergements;
    }

    public function addHebergement(hebergement $hebergement): static
    {
        if (!$this->hebergements->contains($hebergement)) {
            $this->hebergements->add($hebergement);
        }

        return $this;
    }

    public function removeHebergement(hebergement $hebergement): static
    {
        $this->hebergements->removeElement($hebergement);

        return $this;
    }
}
