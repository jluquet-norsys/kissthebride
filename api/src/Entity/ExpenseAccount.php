<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Enums\ExpenseType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[Get]
#[GetCollection]
#[Post]
#[Put]
#[Delete]
#[ORM\Entity]
class ExpenseAccount
{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public \DateTime $expenseDate ;

    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    public Employee $employee;

    #[ORM\ManyToOne]
    #[Assert\NotBlank]
    public Company $company;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    public float $amount;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ExpenseType $type;

    #[ORM\Column]
    #[Assert\NotBlank]
    public \DateTime $createdAt ;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
