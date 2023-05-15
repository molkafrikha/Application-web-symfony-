<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RéponsesRepository;

#[ORM\Entity(repositoryClass: RéponsesRepository::class)]
class Réponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $réponsesId=null;

    #[ORM\Column(length: 50)]
    private ?string $réponse=null;

    #[ORM\ManyToOne(inversedBy:'reponses')]
    private ?User $iduser = null;


    #[ORM\ManyToOne(inversedBy: 'reponsess')]
    #[ORM\JoinColumn(name :'questionId', referencedColumnName :'questionId')]
    private ?Questions $question = null;

   

    public function getRéponsesId(): ?int
    {
        return $this->réponsesId;
    }

    public function getRéponse(): ?string
    {
        return $this->réponse;
    }

    public function setRéponse(string $réponse): self
    {
        $this->réponse = $réponse;

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

    public function getQuestion(): ?Questions
    {
        return $this->question;
    }

    public function setQuestion(?Questions $question): self
    {
        $this->question = $question;

        return $this;
    }


}