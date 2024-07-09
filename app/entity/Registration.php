<?php

namespace Entity;

use Repository\RegistrationRepository;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\Model\Interfaces\EntityInterface;


#[Entity(table: 'registration', repository: RegistrationRepository::class)]
class Registration implements EntityInterface
{

    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
