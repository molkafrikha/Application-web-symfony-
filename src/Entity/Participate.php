<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParticipateRepository;

#[ORM\Entity(repositoryClass: ParticipateRepository::class)]
class Participate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?User $idUsr = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?Evenement $idEvent=null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsr(): ?User
    {
        return $this->idUsr;
    }

    public function setIdUsr(?User $idUsr): self
    {
        $this->idUsr = $idUsr;

        return $this;
    }

    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }


}
