<?php

namespace Sthom\Back\Utils;



/**
 * Trait SingletonTrait
 *
 * Implémente le modèle de conception Singleton pour garantir qu'une seule instance de la classe existe.
 *
 * Elle est utilisée dans les classes:
 * - {@link EntityManager}
 * - {@link Kernel}
 * - {@link Container}
 * @package Sthom\Back\Utils
 */
trait SingletonTrait
{
    /**
     * Instance unique de la classe utilisant ce trait.
     *
     * @var static|null
     */
    private static ?self $instance = null;

    /**
     * Retourne l'instance unique de la classe.
     *
     * @return static L'instance unique de la classe.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructeur privé pour empêcher l'instanciation directe.
     */
    private final function __construct()
    {
    }

    /**
     * Désactive le clonage de l'instance.
     *
     * @throws \Exception
     */
    private function __clone()
    {
        throw new \Exception('Cannot clone a singleton.');
    }

}
