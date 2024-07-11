<?php

namespace Sthom\Back\Parser;

use Exception;
use RuntimeException;

abstract class AbstractReader
{

    private string $namespace;
    private string $dir;


    public function __construct(string $namespace, string $dir)
    {
        $this->namespace = $namespace;
        $this->dir = $dir;
    }

    public final function getNamespace(): string
    {
        return $this->namespace;
    }

    public final function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @throws Exception
     */
    public  final function setNamespace(string $namespace): void
    {
        if (!isset($this->namespace)) {
            $this->namespace = $namespace;
        } else {
            throw new Exception("Namespace is already set");
        }
    }

    /**
     * @throws Exception
     */
    public  final function setDir(string $dir): void
    {
        if (!isset($this->dir)) {
            $this->dir = $dir;
        } else {
            throw new Exception("Directory is already set");
        }
    }

    /**
     * Récupère les fichiers du dossier.
     *
     * @return array Un tableau des fichiers contenus dans le dossier.
     * @throws RuntimeException Si la lecture du répertoire échoue.
     */
    public  final function getFiles(): array
    {
        $files = scandir($this->getDir());

        if ($files === false) {
            throw new RuntimeException("Unable to open  directory: " . self::getDir());
        }

        return array_filter($files, fn($file) => $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php');
    }


}