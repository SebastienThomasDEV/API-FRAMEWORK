<?php

namespace Sthom\Back\App\Entity;

use Sthom\Back\App\Repository\UserRepository;
use Sthom\Back\Kernel\Framework\Annotations\Orm\Entity;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;
use Sthom\Back\Kernel\Framework\Model\Interfaces\UserInterface;



#[Entity(table: 'user', repository: UserRepository::class)]
class User implements UserInterface, EntityInterface
{


    private ?int $id = null;

    private ?string $email;

    private ?string $surname;

    private ?string $name;

    private ?string $pwd;

    private array $addresses = [];
    private ?string $roles;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->roles = $data['roles'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->surname = $data['surname'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->pwd = $data['pwd'] ?? null;
    }

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
