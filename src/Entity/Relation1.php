<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\Relation1Repository;

#[ORM\Entity(repositoryClass: Relation1Repository::class)]
class Relation1
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRelation= null;

    #[ORM\ManyToOne(inversedBy: 'sponsors')]
    #[ORM\JoinColumn(name: 'id_sponsor')]
    private ?Sponsoring $id_sponsor=null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    #[ORM\JoinColumn(name: 'id_evenement')]
    private ?Evenement $id_evenement=null;

    public function getIdRelation(): ?int
    {
        return $this->idRelation;
    }

    public function getIdSponsor(): ?Sponsoring
    {
        return $this->id_sponsor;
    }

    public function setIdSponsor(?Sponsoring $id_sponsor): self
    {
        $this->id_sponsor = $id_sponsor;

        return $this;
    }

    public function getIdEvenement(): ?Evenement
    {
        return $this->id_evenement;
    }

    public function setIdEvenement(?Evenement $id_evenement): self
    {
        $this->id_evenement = $id_evenement;

        return $this;
    }


}
