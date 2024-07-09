<?php

namespace Sthom\Back\Annotations\Methods;

readonly abstract class Route
{
    private string $shortName;
    private string $fn;
    public function __construct(
        private string $path,
        private string $description = '',
        private array $options = []
    ) {
        $this->shortName = (new \ReflectionClass($this))->getShortName();
    }

    public final function getPath(): string
    {
        return $this->path;
    }

    public final function getOptions(): array
    {
        return $this->options;
    }


    public final function getShortName(): string
    {
        return $this->shortName;
    }

    public final function getDescription(): string
    {
        return $this->description;
    }

    public final function getFn(): string
    {
        return $this->fn;
    }

    public final function setFn(string $fn): void
    {
        $this->fn = $fn;
    }




}
