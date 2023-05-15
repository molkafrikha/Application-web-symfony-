<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("events")]
    private ?int $id=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Length(min: 3,  minMessage: "Le lieu doit comporter au moins {{ limit }} caractères.")]
    #[Groups("events")]
    private ?string $lieu = null;

    #[ORM\Column(length:20)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\LessThan(propertyPath: 'datefin', message: "La date de début doit être inférieure à la date de fin.")]
    #[Groups("events")]
    private ?\DateTime $date=null;

    #[ORM\Column(length:20)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Groups("events")]
    private ?\DateTime $datefin=null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "Le titre doit comporter au moins {{ limit }} caractères.")]
    #[Assert\Regex(pattern: '/^[A-Z][A-Za-z0-9_]*$/u', message: "Le nom d'événement doit commencer par une lettre majuscule.")]
    #[Groups("events")]
    private ?string $titre=null;

    #[ORM\Column(length: 300)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide.")]#[Assert\Length(min: 10, minMessage: "La description doit comporter au moins {{ limit }} caractères.")]
    #[Groups("events")]
    private ?string $description=null;

    #[ORM\Column]
    #[Groups("events")]
    private ?int $nbparticipants=0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDatefin(): ?\DateTime
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTime $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbparticipants(): ?int
    {
        return $this->nbparticipants;
    }

    public function setNbparticipants(int $nbparticipants): self
    {
        $this->nbparticipants = $nbparticipants;

        return $this;
    }
   /* public function validationDateFin(ExecutionContextInterface $context, $payload)
    {
        if ($this->datefin && $this->date && $this->datefin < $this->date) {
            $context->buildViolation("La date de fin ne peut pas être antérieure à la date de début.")
                ->atPath('datefin')
                ->addViolation();
        }
    }*/


}
