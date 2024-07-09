<?php

namespace Entity;

use Repository\ReviewRepository;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\Model\Interfaces\EntityInterface;


#[Entity(table: 'review', repository: ReviewRepository::class)]
class Review implements EntityInterface
{


    private ?int $id;


    private ?string $comment;


    private ?int $rating;


    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->comment = $data['comment'] ?? null;
        $this->rating = $data['rating'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'rating' => $this->rating,
        ];
    }
}
