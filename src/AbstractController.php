<?php

namespace Sthom\Back;


/**
 * Classe abstraite AbstractController
 *
 * Fournit des méthodes communes pour les contrôleurs de l'application.
 */
abstract class AbstractController
{
    /**
     * Prépare et renvoie les données sous forme de tableau.
     * Elle s'assure que les objets soient convertis en tableau.
     *
     * @param array $data Les données à envoyer.
     * @return array Les données préparées.
     */
    public final function send(mixed $data): array
    {
        if (is_array($data)) {
            array_walk_recursive($data, function (&$value) {
                if (is_object($value)) {
                    $value = $value->toArray();
                }
            });
        } else {
            $data = [
                'data' => $data
            ];
        }
        return $data;
    }




}
