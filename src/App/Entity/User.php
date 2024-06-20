<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations\OneToMany;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;
use Sthom\Back\Kernel\Framework\Model\Interfaces\UserInterface;


#[Entity(table: 'user')]
class User implements UserInterface, EntityInterface
{
    #[Column(name: 'id', type: 'int')]
    private ?int $id = null;

    #[Column(name: 'roles', type: 'string')]
    private ?string $roles;

    #[Column(name: 'email', type: 'string')]
    private ?string $email;

    #[Column(name: 'surname', type: 'string')]
    private ?string $surname;

    #[Column(name: 'name', type: 'string')]
    private ?string $name;

    #[Column(name: 'pwd', type: 'string')]
    private ?string $pwd;

    #[OneToMany(columnName: 'addresses', targetEntity: Address::class)]
    private array $addresses = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPwd(): string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): void
    {
        $this->pwd = $pwd;
    }

    public final function getIdentifier(): ?string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'roles' => $this->roles,
            'email' => $this->email,
            'surname' => $this->surname,
            'name' => $this->name,
            'pwd' => $this->pwd,
        ];
    }
}
