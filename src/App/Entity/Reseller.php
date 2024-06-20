<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\App\Repository\ResellerRepository;
use Sthom\Back\Kernel\Framework\Annotations\Orm\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;



#[Entity(table: 'reseller', repository: ResellerRepository::class)]
class Reseller implements EntityInterface
{

    private ?int $id = null;


    private ?string $company;


    private ?string $email;


    private ?string $phone;


    private ?string $website;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->company = $data['company'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->website = $data['website'] ?? null;
    }

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
