<?php

namespace Sthom\Back\Kernel\Framework\Utils;

/**
 * Trait SingletonTrait
 *
 * Implémente le modèle de conception Singleton pour garantir qu'une seule instance de la classe existe.
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
    private function __construct()
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
