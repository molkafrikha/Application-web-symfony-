<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SondageRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: SondageRepository::class)]
class Sondage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("sondage")]

    private ?int $sondageId=null;

    #[ORM\Column(length: 20, nullable: true)]


    #[Assert\Length(min: 3, max: 20, minMessage: "Le sujet doit comporter au moins {{ limit }} caractères.", maxMessage: "le sujet doit comporter au maximum {{ limit }} caractères.")]
    ##[Assert\Regex(pattern: '/^[A-Z][A-Za-z0-9_]*$/u', message: "Le sujet doit commencer par une lettre majuscule.")]
    #[Groups("sondage")]
    private string $sujet;

    #[ORM\Column(length: 20, nullable: true)]

    #[Assert\Length(min: 3, max: 20, minMessage: "La categorie doit comporter au moins {{ limit }} caractères.", maxMessage: "v doit comporter au maximum {{ limit }} caractères.")]
    ##[Assert\Regex(pattern: '/^[A-Z][A-Za-z0-9_]*$/u', message: "La categorie doit commencer par une lettre majuscule.")]
    #[Groups("sondage")]
    private ?string $categorie;



    #[ORM\OneToMany(mappedBy: 'Questions', targetEntity: Questions::class)]

    private Collection $questions;


    public function getSondageId(): ?int
    {
        return $this->sondageId;
    }
    public function getSujet(): string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): self
    {
        $this->sujet =$sujet ;
        return $this;
    }
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie =$categorie ;
        return $this;
    }




}