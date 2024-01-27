<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaisonRepository::class)]
class Saison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\OneToMany(mappedBy: 'saison', targetEntity: SaisonDate::class, orphanRemoval: true)]
    private Collection $saisonDates;

    #[ORM\ManyToMany(targetEntity: Tarif::class, mappedBy: 'saisons')]
    private Collection $tarifs;

    #[ORM\ManyToMany(targetEntity: RegleDuree::class, mappedBy: 'saisons')]
    private Collection $regleDurees;

    #[ORM\ManyToMany(targetEntity: RegleOptions::class, mappedBy: 'saisons')]
    private Collection $regleOptions;

    #[ORM\ManyToMany(targetEntity: RegleSejour::class, mappedBy: 'saisons')]
    private Collection $regleSejours;


    public function __construct()
    {
        $this->saisonDates = new ArrayCollection();
        $this->tarifs = new ArrayCollection();
        $this->regleDurees = new ArrayCollection();
        $this->regleOptions = new ArrayCollection();
        $this->regleSejours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    /**
     * @return Collection<int, SaisonDate>
     */
    public function getSaisonDates(): Collection
    {
        return $this->saisonDates;
    }

    public function addSaisonDate(SaisonDate $saisonDate): static
    {
        if (!$this->saisonDates->contains($saisonDate)) {
            $this->saisonDates->add($saisonDate);
            $saisonDate->setSaison($this);
        }

        return $this;
    }

    public function removeSaisonDate(SaisonDate $saisonDate): static
    {
        if ($this->saisonDates->removeElement($saisonDate)) {
            // set the owning side to null (unless already changed)
            if ($saisonDate->getSaison() === $this) {
                $saisonDate->setSaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tarif>
     */
    public function getTarifs(): Collection
    {
        return $this->tarifs;
    }

    public function addTarif(Tarif $tarif): static
    {
        if (!$this->tarifs->contains($tarif)) {
            $this->tarifs->add($tarif);
            $tarif->addSaison($this);
        }

        return $this;
    }

    public function removeTarif(Tarif $tarif): static
    {
        if ($this->tarifs->removeElement($tarif)) {
            $tarif->removeSaison($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RegleDuree>
     */
    public function getRegleDurees(): Collection
    {
        return $this->regleDurees;
    }

    public function addRegleDuree(RegleDuree $regleDuree): static
    {
        if (!$this->regleDurees->contains($regleDuree)) {
            $this->regleDurees->add($regleDuree);
            $regleDuree->addSaison($this);
        }

        return $this;
    }

    public function removeRegleDuree(RegleDuree $regleDuree): static
    {
        if ($this->regleDurees->removeElement($regleDuree)) {
            $regleDuree->removeSaison($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RegleOptions>
     */
    public function getRegleOptions(): Collection
    {
        return $this->regleOptions;
    }

    public function addRegleOption(RegleOptions $regleOption): static
    {
        if (!$this->regleOptions->contains($regleOption)) {
            $this->regleOptions->add($regleOption);
            $regleOption->addSaison($this);
        }

        return $this;
    }

    public function removeRegleOption(RegleOptions $regleOption): static
    {
        if ($this->regleOptions->removeElement($regleOption)) {
            $regleOption->removeSaison($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RegleSejour>
     */
    public function getRegleSejours(): Collection
    {
        return $this->regleSejours;
    }

    public function addRegleSejour(RegleSejour $regleSejour): static
    {
        if (!$this->regleSejours->contains($regleSejour)) {
            $this->regleSejours->add($regleSejour);
            $regleSejour->addSaison($this);
        }

        return $this;
    }

    public function removeRegleSejour(RegleSejour $regleSejour): static
    {
        if ($this->regleSejours->removeElement($regleSejour)) {
            $regleSejour->removeSaison($this);
        }

        return $this;
    }
}
