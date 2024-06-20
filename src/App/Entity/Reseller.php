<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;


#[Entity(table: 'reseller')]
class Reseller implements EntityInterface
{
    #[Column(name: 'id', type: 'int')]
    private ?int $id = null;

    #[Column(name: 'company', type: 'string')]
    private ?string $company;

    #[Column(name: 'email', type: 'string')]
    private ?string $email;

    #[Column(name: 'phone', type: 'string')]
    private ?string $phone;

    #[Column(name: 'website', type: 'string')]
    private ?string $website;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
        ];
    }
}
