<?php

namespace Sthom\Back\Database;


/**
 * Cette classe permet de créer des entités abstraites qui peuvent être hydratées et converties en tableau.
 * Elle permet de simplifier la création d'entités en utilisant la réflexion.
 *
 * @package Sthom\Back\Database
 * @see AbstractRepository
 * @see EntityManager
 * @see AbstractEntity
 */
abstract class AbstractEntity
{
    /**
     * Cette propriété permet de savoir si l'objet est complètement hydraté
     * donc si toutes les propriétés de l'objet ont été initialisées.
     *
     * @var bool
     */
    private bool $isFullyHydrated = false;

    /**
     * Cette propriété permet de savoir si l'objet est initialisé selon son identifiant, il est utilisé pour savoir si l'objet est en base de données.
     *
     * @var bool
     */
    private bool $isInitialized = false;

    /**
     * Cette méthode permet d'hydrater l'objet avec les données passées en paramètre.
     * La méthode __construct est privée pour empêcher l'instanciation directe de la classe.
     *
     * @param array $data
     */
    private final function __construct(array $data = [])
    {
        try {
            $this->hydrate($data);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Cette méthode permet de créer une instance de la classe depuis un tableau de données.
     *
     * @param array $data
     * @return static
     */
    public static function create(array $data = []): self
    {
        return new static($data);
    }

    /**
     * Cette méthode permet de convertir l'objet en tableau utilisable utilisable pour l'insertion en base de données.
     * Basé sur la réflexion, il permet de récupérer les propriétés privées de l'objet.
     *
     * @return array
     */
    public final function toArray(): array {
        $records = array();
        $reflectionClass = new \ReflectionClass($this);
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $records[$property->getName()] = $property->getValue($this);
        }
        return $records;
    }

    /**
     * Cette méthode permet d'hydrater l'objet avec les données passées en paramètre.
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    private function hydrate(array $data): void {
        $reflectionClass = new \ReflectionClass($this);
        $self = $this->toArray();
        $fullyHydrated = true;
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $self)) {
                if ("isFullyHydrated" === $key || "isInitialized" === $key) {
                    continue;
                }
                if ($key === "id" && $value !== null) {
                    $this->setIsInitialized(true);
                }
                $property = $reflectionClass->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($this, $value);
            } else {
                $fullyHydrated = false;
            }
        }
        $this->setIsFullyHydrated($fullyHydrated);
    }

    // Getters and Setters
    public final function isInitialized(): bool
    {
        return $this->isInitialized;
    }

    public final function setIsInitialized(bool $isInitialized): void
    {
        $this->isInitialized = $isInitialized;
    }

    public final function isFullyHydrated(): bool
    {
        return $this->isFullyHydrated;
    }

    public final function setIsFullyHydrated(bool $isFullyHydrated): void
    {
        $this->isFullyHydrated = $isFullyHydrated;
    }





}