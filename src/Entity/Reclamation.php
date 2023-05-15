<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("reclamations")]
    private ?int $id= null;

    #[ORM\Column(length: 25)]
    #[Groups("reclamations")]
    private ?string $intitule=null;

    #[ORM\Column(length: 500)]
    #[Groups("reclamations")]
    private ?string $contenu=null;


    #[ORM\ManyToOne(inversedBy:'demandecovoiturages')]
    #[ORM\JoinColumn(name: 'idUser')]
    #[Groups("reclamations")]
    private ?User $iduser = null;

    #[ORM\Column(length: 255)]
    #[Groups("reclamations")]
    public ?string $image = null;

    #[ORM\Column(length:20)]
    #[Groups("reclamations")]
    private ?\DateTime $date ;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }




}