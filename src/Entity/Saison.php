<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SaisonRepository::class)]
class Saison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['displayHebergement'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['displayHebergement'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['displayHebergement'])]
    private ?string $statut = null;

    #[ORM\OneToMany(mappedBy: 'saison', targetEntity: Periode::class, orphanRemoval: true)]
    private Collection $periodes;

    #[ORM\ManyToMany(targetEntity: Tarif::class, mappedBy: 'saisons')]
    private Collection $tarifs;

    #[ORM\ManyToMany(targetEntity: RegleDuree::class, mappedBy: 'saisons')]
    private Collection $regleDurees;

    #[ORM\ManyToMany(targetEntity: RegleSejour::class, mappedBy: 'saisons')]
    private Collection $regleSejours;

    #[ORM\ManyToMany(targetEntity: OptionMaximum::class, mappedBy: 'saisons')]
    private Collection $optionMaximums;


    public function __construct()
    {
        $this->periodes = new ArrayCollection();
        $this->tarifs = new ArrayCollection();
        $this->regleDurees = new ArrayCollection();
        $this->regleSejours = new ArrayCollection();
        $this->optionMaximums = new ArrayCollection();
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
     * @return Collection<int, Periode>
     */
    public function getPeriodes(): Collection
    {
        return $this->periodes;
    }

    public function addPeriode(Periode $periode): static
    {
        if (!$this->periodes->contains($periode)) {
            $this->periodes->add($periode);
            $periode->setSaison($this);
        }

        return $this;
    }

    public function removePeriode(Periode $periode): static
    {
        if ($this->periodes->removeElement($periode)) {
            // set the owning side to null (unless already changed)
            if ($periode->getSaison() === $this) {
                $periode->setSaison(null);
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

    /**
     * @return Collection<int, OptionMaximum>
     */
    public function getOptionMaximums(): Collection
    {
        return $this->optionMaximums;
    }

    public function addOptionMaximum(OptionMaximum $optionMaximum): static
    {
        if (!$this->optionMaximums->contains($optionMaximum)) {
            $this->optionMaximums->add($optionMaximum);
            $optionMaximum->addSaison($this);
        }

        return $this;
    }

    public function removeOptionMaximum(OptionMaximum $optionMaximum): static
    {
        if ($this->optionMaximums->removeElement($optionMaximum)) {
            $optionMaximum->removeSaison($this);
        }

        return $this;
    }
}
