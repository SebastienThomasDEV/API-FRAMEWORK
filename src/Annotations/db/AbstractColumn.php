<?php

namespace Sthom\Back\Annotations\db;

abstract class AbstractColumn
{
    public function __construct(
        private readonly string $name,
        private readonly string $type,
        public readonly int     $length = 255,
        public readonly bool    $nullable = false,
    ) {}


    public final function getName(): string
    {
        return $this->name;
    }

    public final function getType(): string
    {
        return $this->type;
    }

    public final function getLength(): int
    {
        return $this->length;
    }

    public final function isNullable(): bool
    {
        return $this->nullable;
    }



}