<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]

class Agence
{
    public function __toString()
    {
        return $this->nom;
    }
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id=null;

    
  
    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]


    private ?string $nom=null;

   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]

    private ?string $lieu=null;



    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]

    private ?int $num=null;
    


  
    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    private ?string $email=null;


    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(?int $num): self
    {
        $this->num = $num;

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
