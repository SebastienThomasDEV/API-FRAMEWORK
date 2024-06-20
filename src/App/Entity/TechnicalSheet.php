<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;


#[Entity(table: 'technical_sheet')]
class TechnicalSheet implements EntityInterface
{

    #[Column(name: 'id', type: 'int')]
    private ?int $id = null;

    #[Column(name: 'name', type: 'string')]
    private ?string $name;

    #[Column(name: 'description', type: 'string')]
    private ?string $description;

    #[Column(name: 'weight', type: 'float')]
    private ?float $weight;

    #[Column(name: 'image', type: 'string')]
    private ?string $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'weight' => $this->weight,
            'image' => $this->image,
        ];
    }
}
