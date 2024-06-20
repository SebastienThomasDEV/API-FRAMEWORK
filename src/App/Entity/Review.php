<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;

#[Entity(table: 'reseller')]
class Review implements EntityInterface
{

    #[Column(name: 'id', type: 'int')]
    private ?int $id = null;

    #[Column(name: 'comment', type: 'string')]
    private ?string $comment;

    #[Column(name: 'rating', type: 'int')]
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
