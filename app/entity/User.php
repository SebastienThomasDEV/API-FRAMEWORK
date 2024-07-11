<?php

namespace Sthom\Back\entity;


use Opis\ORM\{
    Entity
};
use Sthom\Back\Annotations\db\Column;
use Sthom\Back\Annotations\db\ColumnType;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Annotations\UserTable;
use Sthom\Back\Utils\UserInterface;

#[UserTable( name: 'users', identifier: 'email')]
class User extends Entity implements UserInterface
{

    #[PrimaryKey('id', ColumnType::INT)]
    private ?int $id = null;

    #[Column('name', ColumnType::STRING)]
    private string $name;

    #[Column('email', ColumnType::STRING)]
    private string $email;

    #[Column('password', ColumnType::STRING)]
    private string $password;

    #[Column('roles', ColumnType::STRING)]
    private string $roles;

    #[Column('created_at', ColumnType::DATETIME)]
    private string $created_at;


    public final function getId(): int
    {
        return $this->id;
    }

    public final function getName(): string
    {
        return $this->name;
    }

    public final function setName(string $name): void
    {
        $this->name = $name;
    }

    public final function getEmail(): string
    {
        return $this->email;
    }

    public final function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public final function getPassword(): string
    {
        return $this->password;
    }

    public final function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public final function getRoles(): string
    {
        return $this->roles;
    }

    public final function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    public final function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public final function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public final function getIdentifier(): ?int
    {
        return $this->id;
    }

}
