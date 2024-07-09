<?php

namespace Entity;

use Repository\AddressRepository;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\Model\Interfaces\EntityInterface;


#[Entity(table: 'address', repository: AddressRepository::class)]
class Address implements EntityInterface
{


    private ?int $id;


    private ?string $street;


    private ?int $streetNumber;


    private ?int $zipCode;


    private ?string $town;


    private ?string $addressComplement;


    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->streetNumber = $data['streetNumber'] ?? null;
        $this->zipCode = $data['zipCode'] ?? null;
        $this->town = $data['town'] ?? null;
        $this->addressComplement = $data['addressComplement'] ?? null;
    }

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
