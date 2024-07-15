<?php

namespace Sthom\Back\Utils;

/**
 * Interface ServiceInterface
 *
 * Cette interface définit un contrat pour les services de l'application.
 * Tous les services doivent implémenter cette interface.
 */
interface ServiceInterface
{
    /**
     * Initialise le service.
     *
     * @return void
     */
    public function initialize(): void;

}
