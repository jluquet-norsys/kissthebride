<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[Get]
#[GetCollection]
#[ORM\Entity]
class Employee
{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $name = '';

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $firstName = '';

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Unique]
    public string $email = '';

    #[ORM\Column(nullable: true)]
    public ?\DateTime $birthDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

}
