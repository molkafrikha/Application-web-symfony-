<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\OffrecovoiturageRepository;
use Symfony\Component\Validator\Constraints\Choice;


#[ORM\Entity(repositoryClass: OffrecovoiturageRepository::class)]
class Offrecovoiturage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]+$/',
        message: "Le champ ne peut contenir que des lettres ou des chiffres."
    )]
    private ?string $matricule=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]+$/',
        message: "Le champ ne peut contenir que des lettres ou des chiffres."
    )]
    private ?string $marque=null;

    #[ORM\Column(length:20)]
    #[Assert\GreaterThan("today", message: "La date de covoiturage ne doit pas être inférieure à la date d'aujourd'hui.")]
    private ?\DateTime $dated=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $lieud=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $lieua=null;

    #[ORM\Column(length: 20)]
    private ?string $dispo=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Choice(choices: [1, 2, 3, 4], message: "Le nombre de places doit être 1, 2, 3 ou 4.")]
    private ?int $nbplace=null;

    #[ORM\Column(length: 8)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]

    private ?int $numtel=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?float $distance=null;

    #[ORM\ManyToOne(inversedBy:'demandecovoiturages')]
    #[ORM\JoinColumn(name: 'idUser')]
    private ?User $iduser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getDated(): ?\DateTime
    {
        return $this->dated;
    }

    public function setDated(\DateTime $dated): self
    {
        $this->dated = $dated;

        return $this;
    }

    public function getLieud(): ?string
    {
        return $this->lieud;
    }

    public function setLieud(string $lieud): self
    {
        $this->lieud = $lieud;

        return $this;
    }

    public function getLieua(): ?string
    {
        return $this->lieua;
    }

    public function setLieua(string $lieua): self
    {
        $this->lieua = $lieua;

        return $this;
    }

    public function getDispo(): ?string
    {
        return $this->dispo;
    }

    public function setDispo(string $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }
    /*public function setDispo(?bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }*/

    public function getNbplace(): ?int
    {
        return $this->nbplace;
    }

    public function setNbplace(int $nbplace): self
    {
        $this->nbplace = $nbplace;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }


}
