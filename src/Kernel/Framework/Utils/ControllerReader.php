<?php

namespace Sthom\Back\Kernel\Framework\Utils;

use Sthom\Back\Kernel\Framework\AbstractRepository;
use Sthom\Back\Kernel\Framework\Annotations\Routing\Route;

/**
 * Classe abstraite ClassReader
 *
 * Fournit une méthode pour lire et récupérer les routes définies dans les contrôleurs.
 */
abstract class ControllerReader
{
    private static string $controllerNamespace;
    private static string $controllerDir;

    /**
     * Initialize ClassReader with configuration.
     *
     * @param string $controllerNamespace Namespace of controllers.
     * @param string $controllerDir Directory of controllers.
     * @return void
     */
    public static function init(string $controllerNamespace, string $controllerDir): void
    {
        self::$controllerNamespace = $controllerNamespace;
        self::$controllerDir = $controllerDir;
    }

    /**
     * Lit les routes définies dans les fichiers de contrôleur.
     *
     * @return array Un tableau contenant les instances des routes trouvées.
     * @throws \RuntimeException Si la lecture des fichiers de contrôleur échoue.
     */
    public final static function readRoutes(): array
    {
        $routes = [];
        try {
            $files = self::getControllerFiles();
        } catch (\RuntimeException $e) {
            Logger::error("Error reading controller files: " . $e->getMessage());
            return [];
        }

        foreach ($files as $filePath) {
            $className = str_replace('.php', '', $filePath);
            $fullClassName = self::$controllerNamespace . $className;

            if (!self::classExists($fullClassName)) {
                Logger::warning("Class $fullClassName does not exist.");
                continue;
            }

            try {
                $controller = new \ReflectionClass($fullClassName);
                $methods = self::getControllerMethods($controller);
            } catch (\ReflectionException $e) {
                Logger::error("Error reflecting class $fullClassName: " . $e->getMessage());
                continue;
            }

            foreach ($methods as $method) {
                $attributes = self::getRouteAttributes($method);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $route->setController($controller->newInstance());
                    try {
                        $services = self::getMethodServices($method);
                    } catch (\Exception $e) {
                        Logger::error("Error getting services for method {$method->getName()} in class $fullClassName: " . $e->getMessage());
                        continue;
                    }
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
     * @throws \RuntimeException Si la lecture du répertoire échoue.
     */
    private static function getControllerFiles(): array
    {
        $files = scandir(self::$controllerDir);
        if ($files === false) {
            throw new \RuntimeException("Unable to open controller directory: " . self::$controllerDir);
        }

        return array_filter($files, fn($file) => $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php');
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
        return array_filter(
            $method->getAttributes(),
            fn($attr) => $attr->getName() === Route::class
        );
    }

    /**
     * Récupère les services d'une méthode.
     *
     * @param \ReflectionMethod $method La méthode.
     * @return array Un tableau des instances de services.
     * @throws \ReflectionException Si l'initialisation de la réflexion échoue.
     */
    private static function getMethodServices(\ReflectionMethod $method): array
    {
        $services = [];
        foreach ($method->getParameters() as $parameter) {
            $parameterType = $parameter->getType();
            if ($parameterType === null) {
                Logger::warning("No type for parameter {$parameter->getName()} in method {$method->getName()}.");
                continue;
            }

            try {
                $class = new \ReflectionClass($parameterType->getName());
            } catch (\ReflectionException $e) {
                Logger::error("Error reflecting parameter type {$parameterType->getName()} for parameter {$parameter->getName()} in method {$method->getName()}: " . $e->getMessage());
                throw $e;
            }

            if ($class->implementsInterface(ServiceInterface::class)) {
                $service = $parameterType->getName();
                $service = $service::getInstance();
                $service->initialize([]);
                $services[] = $service;
            } else if ($class->isSubclassOf(AbstractRepository::class)) {
                $repository = $parameterType->getName();
                $services[] = new $repository();
            }
        }
        return $services;
    }
}
