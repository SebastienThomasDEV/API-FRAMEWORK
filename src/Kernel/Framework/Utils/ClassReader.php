<?php

namespace Sthom\Back\Kernel\Framework\Utils;

/**
 * Classe abstraite ClassReader
 *
 * Fournit une méthode pour lire et récupérer les routes définies dans les contrôleurs.
 */
abstract class ClassReader
{
    const CONTROLLER_NAMESPACE = 'Sthom\\Back\\Controller\\';
    const CONTROLLER_DIR = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Controller';

    /**
     * Lit les routes définies dans les fichiers de contrôleur.
     *
     * @return array Un tableau contenant les instances des routes trouvées.
     */
    public final static function readRoutes(): array
    {
        $routes = [];
        $files = self::getControllerFiles();

        foreach ($files as $filePath) {
            $className = str_replace('.php', '', $filePath);
            $fullClassName = self::CONTROLLER_NAMESPACE . $className;


            if (!self::classExists($fullClassName)) {
                continue;
            }

            $controller = new \ReflectionClass($fullClassName);
            $methods = self::getControllerMethods($controller);


            foreach ($methods as $method) {
                $attributes = self::getRouteAttributes($method);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $route->setController($controller->newInstance());
                    $services = self::getMethodServices($method);
                    $route->setParameters($services);
                    $route->setFn($method->getName());
                    $routes[] = $route;
                }
            }
        }

        return $routes;
    }

    /**
     * Récupère les fichiers de contrôleurs.
     *
     * @return array Un tableau des fichiers de contrôleurs.
     */
    private static function getControllerFiles(): array
    {
        $files = [];
        $dir = opendir(self::CONTROLLER_DIR);
        while ($filePath = readdir($dir)) {
            if ($filePath !== '.' && $filePath !== '..') {
                $files[] = $filePath;
            }
        }
        closedir($dir);
        return $files;
    }

    /**
     * Vérifie si une classe existe.
     *
     * @param string $className Le nom de la classe.
     * @return bool Vrai si la classe existe, faux sinon.
     */
    private static function classExists(string $className): bool
    {
        return class_exists($className);
    }

    /**
     * Récupère les méthodes d'un contrôleur.
     *
     * @param \ReflectionClass $controller La classe de contrôleur.
     * @return array Un tableau des méthodes de la classe.
     */
    private static function getControllerMethods(\ReflectionClass $controller): array
    {
        return $controller->getMethods();
    }

    /**
     * Récupère les attributs de route d'une méthode.
     *
     * @param \ReflectionMethod $method La méthode.
     * @return array Un tableau des attributs de route.
     */
    private static function getRouteAttributes(\ReflectionMethod $method): array
    {
        return array_filter($method->getAttributes(), fn($attr) => $attr->getName() === 'Sthom\Back\Kernel\Framework\Annotations\Route');
    }

    /**
     * Récupère les services d'une méthode.
     *
     * @param \ReflectionMethod $method La méthode.
     * @return array Un tableau des instances de services.
     */
    private static function getMethodServices(\ReflectionMethod $method): array
    {
        $services = [];
        foreach ($method->getParameters() as $parameter) {
            $class = new \ReflectionClass($parameter->getType()->getName());
            if ($class->implementsInterface('Sthom\Back\Kernel\Framework\Utils\ServiceInterface')) {
                $service = $parameter->getType()->getName();
                $service = $service::getInstance();
                $service->initialize([]);
                $services[] = $service;
            } else if ($class->isSubclassOf('Sthom\Back\Kernel\Framework\AbstractRepository')) {
                $repository = $parameter->getType()->getName();
                $services[] = new $repository();
            }
        }
        return $services;
    }
}
