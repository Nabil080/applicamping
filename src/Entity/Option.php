<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\OneToMany(mappedBy: 'option', targetEntity: Tarif::class)]
    private Collection $tarifs;

    #[ORM\OneToMany(mappedBy: 'option', targetEntity: OptionMaximum::class)]
    private Collection $optionMaximums;

    public function __construct()
    {
        $this->tarifs = new ArrayCollection();
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
            $tarif->setOption($this);
        }

        return $this;
    }

    public function removeTarif(Tarif $tarif): static
    {
        if ($this->tarifs->removeElement($tarif)) {
            // set the owning side to null (unless already changed)
            if ($tarif->getOption() === $this) {
                $tarif->setOption(null);
            }
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
            $optionMaximum->setOption($this);
        }

        return $this;
    }

    public function removeOptionMaximum(OptionMaximum $optionMaximum): static
    {
        if ($this->optionMaximums->removeElement($optionMaximum)) {
            // set the owning side to null (unless already changed)
            if ($optionMaximum->getOption() === $this) {
                $optionMaximum->setOption(null);
            }
        }

        return $this;
    }
}
