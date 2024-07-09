<?php

namespace Sthom\App\dto;

class VelomobileDto
{
    public function __construct(
        private string $name,
        private string $description,
    ) {}

}