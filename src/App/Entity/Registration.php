<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\App\Repository\RegistrationRepository;
use Sthom\Back\Kernel\Framework\Annotations\Orm\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;



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
