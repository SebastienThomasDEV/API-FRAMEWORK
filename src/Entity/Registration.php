<?php

namespace Sthom\Back\Entity;

use Sthom\Back\Kernel\Framework\Utils\EntityInterface;

class Registration implements EntityInterface
{
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
