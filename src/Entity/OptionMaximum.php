<?php

namespace App\Entity;

use App\Repository\OptionMaximumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionMaximumRepository::class)]
class OptionMaximum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $ManyToOne = null;

    #[ORM\ManyToOne(inversedBy: 'optionMaximums')]
    private ?option $option = null;

    #[ORM\ManyToMany(targetEntity: Hebergement::class, inversedBy: 'optionMaximums')]
    private Collection $hebergements;

    #[ORM\ManyToMany(targetEntity: Saison::class, inversedBy: 'optionMaximums')]
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

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getManyToOne(): ?string
    {
        return $this->ManyToOne;
    }

    public function setManyToOne(string $ManyToOne): static
    {
        $this->ManyToOne = $ManyToOne;

        return $this;
    }

    public function getOption(): ?option
    {
        return $this->option;
    }

    public function setOption(?option $option): static
    {
        $this->option = $option;

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
