<?php

namespace Sthom\Back\Entity;

class Banx
{
    private ?string $name = null;
    private ?string $surname = null;

    public final function getName(): ?string
    {
        return $this->name;
    }

    public final function getSurname(): ?string
    {
        return $this->surname;
    }

    public final function setName(string $name): void
    {
        $this->name = $name;
    }

    public final function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }
}