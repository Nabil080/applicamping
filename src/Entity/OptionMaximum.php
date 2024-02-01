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

    #[ORM\ManyToMany(targetEntity: emplacement::class, inversedBy: 'optionMaximums')]
    private Collection $emplacements;

    #[ORM\ManyToMany(targetEntity: saison::class, inversedBy: 'optionMaximums')]
    private Collection $saisons;

    public function __construct()
    {
        $this->emplacements = new ArrayCollection();
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
     * @return Collection<int, emplacement>
     */
    public function getEmplacements(): Collection
    {
        return $this->emplacements;
    }

    public function addEmplacement(emplacement $emplacement): static
    {
        if (!$this->emplacements->contains($emplacement)) {
            $this->emplacements->add($emplacement);
        }

        return $this;
    }

    public function removeEmplacement(emplacement $emplacement): static
    {
        $this->emplacements->removeElement($emplacement);

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
