<?php

namespace Sthom\Back;

use Sthom\Back\Utils\SingletonTrait;

/**
 * Cette classe permet de gérer les services de l'application
 * Ces services sont des classes qui fournissent des fonctionnalités à l'application et qui peuvent être partagées entre plusieurs classes
 * Les services sont stockés dans un tableau associatif et disponibles via la méthode {@link get()} à n'importe quel moment de l'exécution du script
 * Cette classe est un singleton
 *
 * @see SingletonTrait
 * @package Sthom\Back
 */
class Container
{
    /**
     * Utilisation du trait SingletonTrait
     */
    use SingletonTrait;

    /**
     * Tableau des services
     *
     * @var array
     */
    private array $services = [];


    /**
     * Cette méthode permet de récupérer un service
     *
     * @param string $service
     * @return object|null|array
     */
    public final function get(string $service): object | null | array
    {
        return $this->services[$service] ?? null;
    }

    /**
     * Cette méthode permet de définir un service
     *
     * @param string $service
     * @param object|array $instance
     * @return void
     */
    public final function set(string $service, object|array $instance): void
    {
        $this->services[$service] = $instance;
    }

}