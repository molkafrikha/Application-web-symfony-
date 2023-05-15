<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaintenanceRepository;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id_maintenance= null;
    

    


    #[ORM\Column(name: "dateDAssurance" )]
    private ?\DateTime $dateDAssurance=null;

    #[ORM\Column(name: "datePAssurance")]
    private ?\DateTime $datePAssurance=null;

    #[ORM\Column(name: "dateDVidange")]
    private ?\DateTime $dateDVidange=null;

    #[ORM\Column]
    private ?int $restekilometre=null;

    #[ORM\ManyToOne(inversedBy:'maintenance')]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[ORM\JoinColumn(name: 'idVoi')]
    private ?Voiture $idVoi = null;
    public function getIdMaintenance(): ?int
    {
        return $this->id_maintenance;
    }



    public function getDatedassurance(): ?\DateTime
    {
        return $this->dateDAssurance;
    }

    public function setDatedassurance(\DateTime $datedassurance): self
    {
        $this->dateDAssurance = $datedassurance;

        return $this;
    }



    public function getDatepassurance(): ?\DateTime
    {
        return $this->datePAssurance;
    }

    public function setDatepassurance(\DateTime $datepassurance): self
    {
        $this->datePAssurance = $datepassurance;

        return $this;
    }

    public function getDatedvidange(): ?\DateTime
    {
        return $this->dateDVidange;
    }

    public function setDatedvidange(\DateTime $datedvidange): self
    {
        $this->dateDVidange = $datedvidange;

        return $this;
    }

    public function getRestekilometre(): ?int
    {
        return $this->restekilometre;
    }

    public function setRestekilometre(int $restekilometre): self
    {
        $this->restekilometre = $restekilometre;

        return $this;
    }


    public function getIdVoi(): ?Voiture
    {
        return $this->idVoi;
    }

    public function setIdVoi(?Voiture $idVoi): self
    {
        $this->idVoi = $idVoi;

        return $this;
    }


}
