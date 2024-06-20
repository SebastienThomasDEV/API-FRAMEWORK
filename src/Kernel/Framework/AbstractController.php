<?php

namespace Sthom\Back\Kernel\Framework;

use ReflectionClass;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;

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
    public final function send(mixed $data): array
    {
        if ($data instanceof EntityInterface) {
            $data = $data->toArray();
        } elseif (is_array($data)) {
            array_walk_recursive($data, function (&$value) {
                if (is_object($value)) {
                    $value = $value->toArray();
                }
            });
        } else {
            $data = [];
        }
        return $data;
    }
}
