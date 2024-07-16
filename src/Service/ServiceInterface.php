<?php

namespace Sthom\Back\Service;

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
     * Cette méthode est appelée automatiquement lors de l'instanciation du service.
     * Elle permet de configurer le service avant son utilisation comme l'initialisation des paramètres utilisés par le service.
     * Généralement les paramètres sont récupérés depuis les variables d'environnement.
     *
     * @return void
     */
    public function initialize(): void;

}
