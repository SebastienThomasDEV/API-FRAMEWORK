<?php


require __DIR__ . '/../vendor/autoload.php';

use Sthom\Back\Kernel;

/**
 * Ce fichier est le point d'entrée de l'application
 * Il permet de lancer le noyau de l'application
 *
 * @see Kernel
 * @package Sthom\Back
 */
$kernel = Kernel::getInstance();
$kernel->run();




