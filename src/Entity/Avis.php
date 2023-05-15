<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvisRepository;

#[ORM\Entity(repositoryClass:AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\ManyToOne(inversedBy: 'avisss')]
    #[ORM\JoinColumn(name: 'idUser')]
    private ?User $iduser = null;

    #[ORM\ManyToOne(inversedBy: 'avisss')]
    #[ORM\JoinColumn(name: 'id_offrecov')]
    public ?Offrecovoiturage $id_offrecov = null;

    #[ORM\Column(length: 255)]
    #[ORM\JoinColumn(name: 'commenraire')]
    private ?string $commenraire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommenraire(): ?string
    {
        return $this->commenraire;
    }

    public function setCommenraire(string $commenraire): self
    {
        $this->commenraire = $commenraire;

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

    public function getId_offrecov(): ?Offrecovoiturage
    {
        return $this->id_offrecov;
    }

    public function setId_offrecov(?Offrecovoiturage $id_offrecov): self
    {
        $this->id_offrecov = $id_offrecov;

        return $this;
    }


}
