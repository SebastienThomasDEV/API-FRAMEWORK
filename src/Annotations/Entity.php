<?php

namespace Sthom\Back\Annotations;
use Attribute;
#[\Attribute(\Attribute::TARGET_CLASS)]
class Entity
{

    public function __construct(
        private readonly string $name,
        private readonly string $repository
    ) {}

    public final function getName(): string
    {
        return $this->name;
    }

    public final function getRepository(): string
    {
        return $this->repository;
    }



}