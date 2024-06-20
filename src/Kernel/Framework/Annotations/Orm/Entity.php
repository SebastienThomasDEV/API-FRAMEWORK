<?php

namespace Sthom\Back\Kernel\Framework\Annotations\Orm;


#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class Entity
{
    public function __construct(
        private string $table,
        private string $repository
    ) {
    }

    public final function getTable(): string
    {
        return $this->table;
    }

    public final function getRepository(): string
    {
        return $this->repository;
    }

}