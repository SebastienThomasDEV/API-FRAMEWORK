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
     * @param array $config Configuration nécessaire pour initialiser le service.
     * @return void
     */
    public function initialize(array $config): void;

    /**
     * Vérifie si le service est correctement initialisé.
     *
     * @return bool True si le service est initialisé, false sinon.
     */
    public function isInitialized(): bool;
}
