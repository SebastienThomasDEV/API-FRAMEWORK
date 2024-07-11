<?php

namespace Sthom\Back;

use Sthom\Back\Utils\SingletonTrait;

class Container
{
    use SingletonTrait;
    private array $services = [];

    public final function get(string $service): object | null
    {
        return $this->services[$service] ?? null;
    }

    public final function set(string $service, object $instance): void
    {
        $this->services[$service] = $instance;
    }

}