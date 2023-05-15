<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

use App\Repository\VoitureRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]


class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\Column(length: 50)]

    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $matricule=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Regex(pattern: '/^[0-9]*$/', message: "le kilometrage doit contenir uniquement des chiffres.")]
    private ?int $puissance=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Regex(pattern: '/^[0-9]*$/', message: "le kilometrage doit contenir uniquement des chiffres.")]
    private ?int $kilometrage=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?int $nbplaces=null;

    #[ORM\Column(name: "dateAssurance", type: "datetime", length: 20)]
    #[Assert\LessThanOrEqual(" now -2days", message: "La date de derniere assurance doit être inférieure à la date d'aujourd'hui.")]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?\DateTime $dateAssurance=null;

    #[ORM\Column(name: "dateDVidange", type: "datetime", length: 20)]
    #[Assert\LessThanOrEqual(" now -2days", message: "La date de derniere vidange doit être inférieure à la date d'aujourd'hui.")]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?\DateTime $dateDVidange=null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $color=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $marque=null;

    #[ORM\ManyToOne(inversedBy:'voitures')]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[ORM\JoinColumn(name: 'idUser')]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy:'voitures')]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[ORM\JoinColumn(name: 'idagence')]
    private ?Agence $idagence = null;

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

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): self
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }



    public function getNbplaces(): ?int
    {
        return $this->nbplaces;
    }

    public function setNbplaces(int $nbplaces): self
    {
        $this->nbplaces = $nbplaces;

        return $this;
    }

    public function getDateAssurance(): ?\DateTime
    {
        return $this->dateAssurance;
    }

    public function setDateAssurance(\DateTime $dateAssurance): self
    {
        $this->dateAssurance = $dateAssurance;

        return $this;
    }

    public function getDateDVidange(): ?\DateTime
    {
        return $this->dateDVidange;
    }

    public function setDateDVidange(\DateTime $dateDVidange): self
    {
        $this->dateDVidange = $dateDVidange;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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

    public function getIduser(): ?User
    {
        return $this->idUser;
    }

    public function setIduser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdagence(): ?Agence
    {
        return $this->idagence;
    }

    public function setIdagence(?Agence $idagence): self
    {
        $this->idagence = $idagence;


        return $this;
    }


}
