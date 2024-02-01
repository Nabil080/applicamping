<?php

namespace App\Entity;

use App\Repository\EmplacementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EmplacementRepository::class)]
#[UniqueEntity('numero')]
class Emplacement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'emplacements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hebergement $hebergement = null;

    #[ORM\OneToMany(mappedBy: 'emplacement', targetEntity: reservation::class)]
    private Collection $reservation;

    #[ORM\OneToMany(mappedBy: 'emplacement', targetEntity: Tarif::class)]
    private Collection $tarifs;

    #[ORM\ManyToMany(targetEntity: tag::class, inversedBy: 'emplacements')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: OptionMaximum::class, mappedBy: 'emplacements')]
    private Collection $optionMaximums;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->tarifs = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->optionMaximums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

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

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): static
    {
        $this->hebergement = $hebergement;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(reservation $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setEmplacement($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEmplacement() === $this) {
                $reservation->setEmplacement(null);
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
            $tarif->setEmplacement($this);
        }

        return $this;
    }

    public function removeTarif(Tarif $tarif): static
    {
        if ($this->tarifs->removeElement($tarif)) {
            // set the owning side to null (unless already changed)
            if ($tarif->getEmplacement() === $this) {
                $tarif->setEmplacement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(tag $tag): static
    {
        $this->tags->removeElement($tag);

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
            $optionMaximum->addEmplacement($this);
        }

        return $this;
    }

    public function removeOptionMaximum(OptionMaximum $optionMaximum): static
    {
        if ($this->optionMaximums->removeElement($optionMaximum)) {
            $optionMaximum->removeEmplacement($this);
        }

        return $this;
    }
}
