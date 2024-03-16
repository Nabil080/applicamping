<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToMany(targetEntity: Emplacement::class, mappedBy: 'tags')]
    private Collection $emplacements;

    public function __construct()
    {
        $this->emplacements = new ArrayCollection();
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
            $emplacement->addTag($this);
        }

        return $this;
    }

    public function removeEmplacement(Emplacement $emplacement): static
    {
        if ($this->emplacements->removeElement($emplacement)) {
            $emplacement->removeTag($this);
        }

        return $this;
    }
}
