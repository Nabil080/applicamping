<?php

namespace App\Entity;

use App\Repository\TarifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifRepository::class)]
class Tarif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column]
    private ?bool $par_nuit = null;

    #[ORM\Column]
    private ?bool $par_personne = null;

    #[ORM\ManyToOne(inversedBy: 'tarifs')]
    private ?emplacement $emplacement = null;

    #[ORM\Column]
    private ?bool $adulte = null;

    #[ORM\Column]
    private ?bool $enfant = null;

    #[ORM\ManyToOne(inversedBy: 'tarifs')]
    private ?option $_option = null;

    #[ORM\ManyToMany(targetEntity: saison::class, inversedBy: 'tarifs')]
    private Collection $saisons;

    public function __construct()
    {
        $this->saisons = new ArrayCollection();
    }

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

    public function isParNuit(): ?bool
    {
        return $this->par_nuit;
    }

    public function setParNuit(bool $par_nuit): static
    {
        $this->par_nuit = $par_nuit;

        return $this;
    }

    public function isParPersonne(): ?bool
    {
        return $this->par_personne;
    }

    public function setParPersonne(bool $par_personne): static
    {
        $this->par_personne = $par_personne;

        return $this;
    }

    public function getEmplacement(): ?emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?emplacement $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function isAdulte(): ?bool
    {
        return $this->adulte;
    }

    public function setAdulte(bool $adulte): static
    {
        $this->adulte = $adulte;

        return $this;
    }

    public function isEnfant(): ?bool
    {
        return $this->enfant;
    }

    public function setEnfant(bool $enfant): static
    {
        $this->enfant = $enfant;

        return $this;
    }

    public function getOption(): ?option
    {
        return $this->_option;
    }

    public function setOption(?option $_option): static
    {
        $this->_option = $_option;

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
}
