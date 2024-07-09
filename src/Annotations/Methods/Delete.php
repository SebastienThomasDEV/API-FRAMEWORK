<?php

namespace Sthom\Back\Annotations\Methods;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
readonly class Delete extends Route implements MethodInterface
{
    const COLOR = 'bg-red-500';
    public function __construct(string $path, string $description = '', array $options = []) {
        parent::__construct($path, $description, $options);
    }
}