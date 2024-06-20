<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\App\Repository\TechnicalSheetRepository;
use Sthom\Back\Kernel\Framework\Annotations\Orm\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;



#[Entity(table: 'technical_sheet', repository: TechnicalSheetRepository::class)]
class TechnicalSheet implements EntityInterface
{


    private ?int $id;


    private ?string $name;


    private ?string $description;


    private ?float $weight;


    private ?string $image;


    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->weight = $data['weight'] ?? null;
        $this->image = $data['image'] ?? null;
    }

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
