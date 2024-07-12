<?php

namespace Sthom\Back\Annotations;

#[\Attribute(\Attribute::TARGET_CLASS)]
class UserEntity extends Entity
{

    public function __construct(readonly string $name, readonly string $repository, readonly string $identifier) {
        parent::__construct($this->name, $this->repository);
    }

    public final function getIdentifier(): string
    {
        return $this->identifier;
    }

}