<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\DemandecovoiturageRepository;

#[ORM\Entity(repositoryClass: DemandecovoiturageRepository::class)]
class Demandecovoiturage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

    
    #[ORM\Column(length:20)]
    private ?\DateTime $datereservation=null;

    #[ORM\Column]
    private ?int $nbplace=null;

    #[ORM\ManyToOne(inversedBy: 'Offrecovoiturage')]
    private ?Offrecovoiturage $offre=null;


    #[ORM\ManyToOne(inversedBy:'demandes')]
    #[ORM\JoinColumn(name: 'user_id')]
    private ?User $user_id = null;

    public function setUser(User $u){
        $this->user = $u;
    }

    public function getUser(){
        return $this->user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatereservation(): ?\DateTime
    {
        return $this->datereservation;
    }

    public function setDatereservation(\DateTime $datereservation): self
    {
        $this->datereservation = $datereservation;

        return $this;
    }

    public function getNbplace(): ?int
    {
        return $this->nbplace;
    }

    public function setNbplace(int $nbplace): self
    {
        $this->nbplace = $nbplace;

        return $this;
    }

    public function getIdOffre(): ?Offrecovoiturage
    {
        return $this->offre;
    }

    public function setIdOffre(?Offrecovoiturage $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->user_id;
    }

    public function setIdUser(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }


}
