<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FactureRepository;
use App\Entity\Reservations;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idFacture = null;

    #[ORM\Column]
    private ?int $numfacture = null;

    #[ORM\Column]
    private ?float $montantFacture = null;

    #[ORM\Column(length: 250 ,nullable: true)]
    private ?string $datePaiement = null;

    #[ORM\ManyToOne(targetEntity: Reservations::class)]
    #[ORM\JoinColumn(name: 'idreservation', referencedColumnName: 'idreservation')]
    private ?Reservations $numres = null;


    public function getIdFacture(): ?int
    {
        return $this->idFacture;
    }

    public function getNumfacture(): ?int
    {
        return $this->numfacture;
    }

    public function setNumfacture(int $numfacture): static
    {
        $this->numfacture = $numfacture;

        return $this;
    }

    public function getMontantFacture(): ?float
    {
        return $this->montantFacture;
    }

    public function setMontantFacture(float $montantFacture): static
    {
        $this->montantFacture = $montantFacture;

        return $this;
    }

    public function getDatePaiement(): ?string
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(string $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getNumres(): ?Reservations
    {
        return $this->numres;
    }

    public function setNumres(?Reservations $numres): static
    {
        $this->numres = $numres;

        return $this;
    }
}

