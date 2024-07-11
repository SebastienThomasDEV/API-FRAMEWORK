<?php

namespace Sthom\Back\Annotations;
use Attribute;
#[\Attribute(\Attribute::TARGET_CLASS)]
class Table
{

    public function __construct(

        private readonly string $name,
    ) {}

    public final function getName(): string
    {
        return $this->name;
    }



}