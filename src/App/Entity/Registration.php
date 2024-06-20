<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;


#[Entity(table: 'registration')]
class Registration implements EntityInterface
{
    #[Column(name: 'id', type: 'int')]
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
