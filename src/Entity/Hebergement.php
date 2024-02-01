<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $minimum = null;

    #[ORM\Column(nullable: true)]
    private ?int $maximum = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\OneToMany(mappedBy: 'hebergement', targetEntity: Emplacement::class, orphanRemoval: true)]
    private Collection $emplacements;

    #[ORM\ManyToMany(targetEntity: RegleDuree::class, mappedBy: 'hebergements')]
    private Collection $regleDurees;

    #[ORM\ManyToMany(targetEntity: RegleSejour::class, mappedBy: 'hebergements')]
    private Collection $regleSejours;

    public function __construct()
    {
        $this->emplacements = new ArrayCollection();
        $this->regleDurees = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function setMaximum(?int $maximum): static
    {
        $this->maximum = $maximum;

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
     * @return Collection<int, Emplacement>
     */
    public function getEmplacements(): Collection
    {
        return $this->emplacements;
    }

    public function addEmplacement(Emplacement $emplacement): static
    {
        if (!$this->emplacements->contains($emplacement)) {
            $this->emplacements->add($emplacement);
            $emplacement->setHebergement($this);
        }

        return $this;
    }

    public function removeEmplacement(Emplacement $emplacement): static
    {
        if ($this->emplacements->removeElement($emplacement)) {
            // set the owning side to null (unless already changed)
            if ($emplacement->getHebergement() === $this) {
                $emplacement->setHebergement(null);
            }
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
            $regleDuree->addHebergement($this);
        }

        return $this;
    }

    public function removeRegleDuree(RegleDuree $regleDuree): static
    {
        if ($this->regleDurees->removeElement($regleDuree)) {
            $regleDuree->removeHebergement($this);
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
            $regleSejour->addHebergement($this);
        }

        return $this;
    }

    public function removeRegleSejour(RegleSejour $regleSejour): static
    {
        if ($this->regleSejours->removeElement($regleSejour)) {
            $regleSejour->removeHebergement($this);
        }

        return $this;
    }
}
