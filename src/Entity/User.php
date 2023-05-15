<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['jsonable'])]
    private ?int $id = null;
    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message:"Last name is required")]
    #[Groups(['jsonable'])]
    private ?string $nom = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message:"Fist name is required")]
    #[Groups(['jsonable'])]
    private ?string $prenom = null;


    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message:"Email is required")]
    #[Assert\Email(message:"The email '{{ value }}' is not a valid email ")]
    #[Groups(['jsonable'])]
    private ?string $email = null;



    /**
     * @var string The hashed password
     */
    #[ORM\Column(length:255, unique: true)]

    private ?string $password = '';


    #[ORM\Column]
    #[Groups(['jsonable'])]
    private array $roles = [];

    #[ORM\Column]
    #[Groups(['jsonable'])]
    private ?int $age = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $resettoken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     * @return self
     */
    public function setNom(?string $nom): self {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     * @return self
     */
    public function setPrenom(?string $prenom): self {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int {
        return $this->age;
    }

    /**
     * @param int|null $age
     * @return self
     */
    public function setAge(?int $age): self {
        $this->age = $age;
        return $this;
    }


    public function getresettoken(): ?string {
        return $this->resettoken;
    }


    public function setresettoken(?string $resettoken): self {
        $this->resettoken = ($resettoken !== '') ? $resettoken : null;
        return $this;
    }


}
