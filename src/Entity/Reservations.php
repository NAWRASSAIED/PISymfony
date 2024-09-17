<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType; 

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $idreservation;
   
    #[ORM\Column(nullable:true)]
    private ?int $cinclient;

    #[ORM\Column(length: 150)]
    private ?string $nomclient;

    #[ORM\Column]
    private ?int $nombrepersonnes;

    #[ORM\Column(name: "dateDebut", type: "date", nullable: true)]
    private ?\DateTime $datedebut;

    #[ORM\Column(name: "dateFin", type: "date", nullable: true)]
    private ?\DateTime $datefin;


    #[ORM\Column(length: 250)]
    private ?string $modePaiement;

    #[ORM\Column(length: 254)]
    private ?string $typehebergement;

    #[ORM\Column(length: 254)]
    private ?string $typeactivite;

    #[ORM\Column]
    private ?int $numtel;

    public function getIdreservation(): ?int
    {
        return $this->idreservation;
    }

    public function getCinclient(): ?int
    {
        return $this->cinclient;
    }

    public function setCinclient(int $cinclient): static
    {
        $this->cinclient = $cinclient;

        return $this;
    }

    public function getNomclient(): ?string
    {
        return $this->nomclient;
    }

    public function setNomclient(string $nomclient): static
    {
        $this->nomclient = $nomclient;

        return $this;
    }

    public function getNombrepersonnes(): ?int
    {
        return $this->nombrepersonnes;
    }

    public function setNombrepersonnes(int $nombrepersonnes): static
    {
        $this->nombrepersonnes = $nombrepersonnes;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
{
    return $this->datedebut;
}

public function setDatedebut(?\DateTimeInterface $datedebut): static
{
    $this->datedebut = $datedebut;

    return $this;
}

public function getDatefin(): ?\DateTimeInterface
{
    return $this->datefin;
}

public function setDatefin(?\DateTimeInterface $datefin): static
{
    $this->datefin = $datefin;

    return $this;
}

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getTypehebergement(): ?string
    {
        return $this->typehebergement;
    }

    public function setTypehebergement(string $typehebergement): static
    {
        $this->typehebergement = $typehebergement;

        return $this;
    }

    public function getTypeactivite(): ?string
    {
        return $this->typeactivite;
    }

    public function setTypeactivite(string $typeactivite): static
    {
        $this->typeactivite = $typeactivite;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }
    public function __toString()
   {
    return (string) $this->idreservation;
   }


}
