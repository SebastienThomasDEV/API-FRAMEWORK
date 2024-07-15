<?php

namespace Sthom\Back\Service;

use Sthom\Back\Utils\ServiceInterface;

class PasswordHasher implements ServiceInterface
{
    private string $algo;
    private array $options;

    public final function initialize(): void
    {
        $this->algo = PASSWORD_BCRYPT;
        $this->options = [
            'cost' => 12,
        ];
    }

    public final function hash(string $password): string
    {
        return password_hash($password, $this->algo, $this->options);
    }

    public final function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public final function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, $this->algo, $this->options);
    }

    public final function setAlgo(string $algo): void
    {
        $this->algo = $algo;
    }

    public final function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public final function getAlgo(): string
    {
        return $this->algo;
    }

    public final function getOptions(): array
    {
        return $this->options;
    }

    public final function __construct()
    {
        $this->initialize();
    }



}