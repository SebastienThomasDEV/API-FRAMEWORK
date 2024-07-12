<?php

namespace Sthom\Back\entity;


use Sthom\Back\Annotations\db\Column;
use Sthom\Back\Annotations\db\ColumnType;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\Database\AbstractEntity;
use Sthom\Back\Database\UserInterface;
use Sthom\Back\repository\UserRepository;

#[Entity(name: 'users', repository: UserRepository::class)]
class User extends AbstractEntity implements UserInterface
{

    #[PrimaryKey('id', ColumnType::INT)]
    private ?int $id = null;

    #[Column('name', ColumnType::STRING)]
    private ?string $name = null;

    #[Column('email', ColumnType::STRING)]
    private ?string $email = null;

    #[Column('password', ColumnType::STRING)]
    private ?string $password = null;

    #[Column('role', ColumnType::STRING)]
    private ?string $role = null;

    #[Column('created_at', ColumnType::DATETIME)]
    private ?string $created_at = null;


    public final function getId(): ?int
    {
        return $this->id;
    }

    public final function getName(): ?string
    {
        return $this->name;
    }

    public final function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public final function getEmail(): ?string
    {
        return $this->email;
    }

    public final function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public final function getPassword(): ?string
    {
        return $this->password;
    }

    public final function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public final function getRole(): ?string
    {
        return $this->role;
    }

    public final function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public final function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public final function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }


}
