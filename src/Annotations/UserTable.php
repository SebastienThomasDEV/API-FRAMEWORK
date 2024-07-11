<?php

namespace Sthom\Back\Annotations;

#[\Attribute(\Attribute::TARGET_CLASS)]
class UserTable extends Table
{

    public function __construct(readonly string $name, readonly private string $identifier) {
        parent::__construct($this->name);
    }

    public final function getIdentifier(): string
    {
        return $this->identifier;
    }

}