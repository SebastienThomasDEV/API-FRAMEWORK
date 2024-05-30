<?php

namespace Sthom\Back\Kernel\Framework;

use ReflectionClass;

/**
 * Classe abstraite AbstractController
 *
 * Fournit des méthodes communes pour les contrôleurs de l'application.
 */
abstract class AbstractController
{
    /**
     * Prépare et renvoie les données sous forme de tableau.
     *
     * @param array $data Les données à envoyer.
     * @return array Les données préparées.
     */
    public final function send(array $data): array
    {
        array_walk_recursive($data, function (&$value) {
            if (is_object($value)) {
                $value = $this->objectToArray($value);
            }
        });
        return $data;
    }

    /**
     * Convertit un objet en tableau associatif.
     *
     * @param object $object L'objet à convertir.
     * @return array Le tableau associatif représentant l'objet.
     */
    private function objectToArray(object $object): array
    {
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
        }

        return $array;
    }
}
