<?php

namespace App\Entity;

use App\Repository\RegleDureeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegleDureeRepository::class)]
class RegleDuree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $maximum = null;

    #[ORM\Column(nullable: true)]
    private ?int $minimum = null;

    #[ORM\ManyToMany(targetEntity: Saison::class, inversedBy: 'regleDurees')]
    private Collection $saisons;

    #[ORM\ManyToMany(targetEntity: Hebergement::class, inversedBy: 'regleDurees')]
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

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function setMaximum(?int $maximum): static
    {
        $this->maximum = $maximum;

        return $this;
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function setMinimum(?int $minimum): static
    {
        $this->minimum = $minimum;

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
}
