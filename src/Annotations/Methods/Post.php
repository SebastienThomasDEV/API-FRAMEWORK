<?php

namespace Sthom\Back\Annotations\Methods;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
readonly class Post extends Route implements MethodInterface
{
    const COLOR = 'bg-blue-500';
    public function __construct(string $path, string $description = '', array $options = []) {
        parent::__construct($path, $description, $options);
    }

}