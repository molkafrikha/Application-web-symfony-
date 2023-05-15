<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VerficationRepository;

#[ORM\Entity(repositoryClass: VerificationRepository::class)]
class Verification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\Column(length: 20)]
    private ?string $code=null;

    #[ORM\ManyToOne(inversedBy:'verification')]
    private ?User $iduser = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
