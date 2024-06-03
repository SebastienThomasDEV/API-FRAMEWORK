<?php

namespace Sthom\Back\Entity;

use Sthom\Back\Kernel\Framework\Utils\EntityInterface;

class Review implements EntityInterface
{
    private ?int $id = null;
    private ?string $comment;
    private ?int $rating;

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
