<?php

namespace Sthom\Back\Annotations\Methods;

interface MethodInterface
{

    public function __construct(string $path, string $description = '', array $options = []);

    public function getPath(): string;

    public function getOptions(): array;

    public function getDescription(): string;

    public function getShortName(): string;


}