<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\SponsoringRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponsoringRepository::class)]
class Sponsoring
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Length(min: 3, max: 20, minMessage: "Le sponsor doit comporter au moins {{ limit }} caractères.", maxMessage: "Le sponsor doit comporter au maximum {{ limit }} caractères.")]
    #[Assert\Regex(pattern: '/^[A-Z][A-Za-z0-9_]*$/u', message: "Le sponsor doit commencer par une lettre majuscule.")]
    private ?string $sponsor=null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le champ ne peut pas être vide.")]
    #[Assert\Range(min: 1, max: 9999, notInRangeMessage: "Le montant doit être compris entre {{ min }} et {{ max }}.")]
    #[Assert\Regex(pattern: '/^[0-9]*$/', message: "Le montant doit contenir uniquement des chiffres.")]
    private ?float $montant=null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Length(min: 4, minMessage: "Le sponsor doit comporter au moins {{ limit }} caractères.")]
    private ?string $adresse=null;

    #[ORM\Column(length:20)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\EqualTo("today", message: "La date de signature doit être égale à la date d'aujourd'hui.")]
    private ?\DateTime $datesignature=null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email=null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSponsor(): ?string
    {
        return $this->sponsor;
    }

    public function setSponsor(?string $sponsor): self
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDatesignature(): ?\DateTime
    {
        return $this->datesignature;
    }

    public function setDatesignature(?\DateTime $datesignature): self
    {
        $this->datesignature = $datesignature;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


}
