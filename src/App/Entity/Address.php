<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations\ManyToOne;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;


#[Entity(table: 'address')]
class Address implements EntityInterface
{

    #[Column(name: 'id', type: 'int')]
    private ?int $id = null;

    #[Column(name: 'street', type: 'string')]
    private ?string $street = null;

    #[Column(name: 'street_number', type: 'string')]
    private ?int $streetNumber = null;

    #[Column(name: 'zip_code', type: 'int')]
    private ?int $zipCode = null;

    #[Column(name: 'town', type: 'string')]
    private ?string $town = null;

    #[Column(name: 'address_complement', type: 'string')]
    private ?string $addressComplement = null;

    #[ManyToOne(columnName: 'user_id', mappedBy:User::class)]
    private ?int $userId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getStreetNumber(): int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    public function getZipCode(): int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getTown(): string
    {
        return $this->town;
    }

    public function setTown(string $town): void
    {
        $this->town = $town;
    }

    public function getAddressComplement(): ?string
    {
        return $this->addressComplement;
    }

    public function setAddressComplement(?string $addressComplement): void
    {
        $this->addressComplement = $addressComplement;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'streetNumber' => $this->streetNumber,
            'zipCode' => $this->zipCode,
            'town' => $this->town,
            'addressComplement' => $this->addressComplement
        ];
    }
}
