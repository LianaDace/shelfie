<?php

namespace App\Entity;

use App\Enum\BookStatus;
use App\Repository\BookRepository;
use BcMath\Number;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Title is required")]
    #[Assert\Length(min: 1, max: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Author is required")]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $author = null;

    #[ORM\Column(enumType: BookStatus::class)]
    private BookStatus $status;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int$rating = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getStatus(): BookStatus
    {
        return $this->status;
    }

    public function setStatus(BookStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?Number $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
