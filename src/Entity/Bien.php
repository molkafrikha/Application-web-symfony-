<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BienRepository;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: BienRepository::class)]
class Bien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

    
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $lieud=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $lieua=null;

    #[ORM\Column(length:20)]
    #[Assert\GreaterThan("today", message: "La date de covoiturage ne doit pas être inférieure à la date d'aujourd'hui.")]
    private ?\DateTime $dated=null;
    

    #[ORM\Column(length: 8)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $num=null;

    #[ORM\ManyToOne(inversedBy:'biens')]
    #[ORM\JoinColumn(name: 'idUser')]
    private ?User $iduser = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDated(): ?\DateTime
    {
        return $this->dated;
    }

    public function setDated(\DateTime $dated): self
    {
        $this->dated = $dated;

        return $this;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

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
