<?php

namespace Sthom\Back\Parser;

use Exception;
use RuntimeException;

/**
 * Cette classe abstraite sera étendue par les classes {@link ControllerReader} et {@link EntityReader}.
 * Elle définit les méthodes nécessaires pour lire les fichiers d'un dossier.
 *
 * @package Sthom\Back\Parser
 */
abstract class AbstractReader
{

    /**
     * Le namespace des classes à lire.
     *
     * @var string
     */
    private string $namespace;

    /**
     * Le dossier contenant les classes à lire.
     *
     * @var string
     */
    private string $dir;

    /**
     * Constructeur de la classe AbstractReader.
     *
     * @param string $namespace Le namespace des classes à lire.
     * @param string $dir Le dossier contenant les classes à lire.
     */
    public function __construct(string $namespace, string $dir)
    {
        $this->namespace = $namespace;
        $this->dir = $dir;
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

    // Getters
    public final function getNamespace(): string
    {
        return $this->namespace;
    }

    public final function getDir(): string
    {
        return $this->dir;
    }


}