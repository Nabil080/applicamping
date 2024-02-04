<?php

namespace App\Entity;

use App\Repository\RegleReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegleReservationRepository::class)]
class RegleReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $client_nom = null;

    #[ORM\Column]
    private ?bool $client_prenom = null;

    #[ORM\Column]
    private ?bool $client_email = null;

    #[ORM\Column]
    private ?bool $client_telephone = null;

    #[ORM\Column]
    private ?bool $client_adresse = null;

    #[ORM\Column]
    private ?bool $emplacement_libre = null;

    #[ORM\Column]
    private ?bool $emplacement_aleatoire = null;

    #[ORM\Column]
    private ?bool $emplacement_defini = null;

    #[ORM\Column]
    private ?bool $paiement_carte_bancaire = null;

    #[ORM\Column]
    private ?bool $paiement_virement_bancaire = null;

    #[ORM\Column]
    private ?bool $paiement_cheque_vacance = null;

    #[ORM\Column]
    private ?bool $paiement_cheque = null;

    #[ORM\Column]
    private ?bool $paiement_espece = null;

    #[ORM\Column]
    private ?bool $acompte = null;

    #[ORM\Column]
    private ?bool $acompte_seul = null;

    #[ORM\Column(nullable: true)]
    private ?int $acompte_montant = null;

    #[ORM\Column]
    private ?int $taxe_sejour_montant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isClientNom(): ?bool
    {
        return $this->client_nom;
    }

    public function setClientNom(bool $client_nom): static
    {
        $this->client_nom = $client_nom;

        return $this;
    }

    public function isClientPrenom(): ?bool
    {
        return $this->client_prenom;
    }

    public function setClientPrenom(bool $client_prenom): static
    {
        $this->client_prenom = $client_prenom;

        return $this;
    }

    public function isClientEmail(): ?bool
    {
        return $this->client_email;
    }

    public function setClientEmail(bool $client_email): static
    {
        $this->client_email = $client_email;

        return $this;
    }

    public function isClientTelephone(): ?bool
    {
        return $this->client_telephone;
    }

    public function setClientTelephone(bool $client_telephone): static
    {
        $this->client_telephone = $client_telephone;

        return $this;
    }

    public function isClientAdresse(): ?bool
    {
        return $this->client_adresse;
    }

    public function setClientAdresse(bool $client_adresse): static
    {
        $this->client_adresse = $client_adresse;

        return $this;
    }

    public function isEmplacementLibre(): ?bool
    {
        return $this->emplacement_libre;
    }

    public function setEmplacementLibre(bool $emplacement_libre): static
    {
        $this->emplacement_libre = $emplacement_libre;

        return $this;
    }

    public function isEmplacementAleatoire(): ?bool
    {
        return $this->emplacement_aleatoire;
    }

    public function setEmplacementAleatoire(bool $emplacement_aleatoire): static
    {
        $this->emplacement_aleatoire = $emplacement_aleatoire;

        return $this;
    }

    public function isEmplacementDefini(): ?bool
    {
        return $this->emplacement_defini;
    }

    public function setEmplacementDefini(bool $emplacement_defini): static
    {
        $this->emplacement_defini = $emplacement_defini;

        return $this;
    }

    public function isPaiementCarteBancaire(): ?bool
    {
        return $this->paiement_carte_bancaire;
    }

    public function setPaiementCarteBancaire(bool $paiement_carte_bancaire): static
    {
        $this->paiement_carte_bancaire = $paiement_carte_bancaire;

        return $this;
    }

    public function isPaiementVirementBancaire(): ?bool
    {
        return $this->paiement_virement_bancaire;
    }

    public function setPaiementVirementBancaire(bool $paiement_virement_bancaire): static
    {
        $this->paiement_virement_bancaire = $paiement_virement_bancaire;

        return $this;
    }

    public function isPaiementChequeVacance(): ?bool
    {
        return $this->paiement_cheque_vacance;
    }

    public function setPaiementChequeVacance(bool $paiement_cheque_vacance): static
    {
        $this->paiement_cheque_vacance = $paiement_cheque_vacance;

        return $this;
    }

    public function isPaiementCheque(): ?bool
    {
        return $this->paiement_cheque;
    }

    public function setPaiementCheque(bool $paiement_cheque): static
    {
        $this->paiement_cheque = $paiement_cheque;

        return $this;
    }

    public function isPaiementEspece(): ?bool
    {
        return $this->paiement_espece;
    }

    public function setPaiementEspece(bool $paiement_espece): static
    {
        $this->paiement_espece = $paiement_espece;

        return $this;
    }

    public function isAcompte(): ?bool
    {
        return $this->acompte;
    }

    public function setAcompte(bool $acompte): static
    {
        $this->acompte = $acompte;

        return $this;
    }

    public function isAcompteSeul(): ?bool
    {
        return $this->acompte_seul;
    }

    public function setAcompteSeul(bool $acompte_seul): static
    {
        $this->acompte_seul = $acompte_seul;

        return $this;
    }

    public function getAcompteMontant(): ?int
    {
        return $this->acompte_montant;
    }

    public function setAcompteMontant(?int $acompte_montant): static
    {
        $this->acompte_montant = $acompte_montant;

        return $this;
    }

    public function getTaxeSejourMontant(): ?int
    {
        return $this->taxe_sejour_montant;
    }

    public function setTaxeSejourMontant(int $taxe_sejour_montant): static
    {
        $this->taxe_sejour_montant = $taxe_sejour_montant;

        return $this;
    }
}
