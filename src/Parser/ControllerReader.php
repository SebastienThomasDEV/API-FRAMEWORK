<?php

namespace Sthom\Back\Parser;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Sthom\Back\AbstractRepository;
use Sthom\Back\Annotations\middlewares\RouteMiddlewareAnnotation;
use Sthom\Back\Annotations\Route;
use Sthom\Back\Container;
use Sthom\Back\Utils\Logger;
use Sthom\Back\Utils\ServiceInterface;

/**
 * Classe abstraite ClassReader
 *
 * Fournit une méthode pour lire et récupérer les routes définies dans les contrôleurs.
 */
class ControllerReader extends AbstractReader
{
    /**
     * Lit les routes définies dans les fichiers de contrôleur.
     *
     * @return array Un tableau contenant les instances des routes trouvées.
     * @throws RuntimeException Si la lecture des fichiers de contrôleur échoue.
     */
    public final function readRoutes(): array
    {
        $routes = [];
        try {
            $files = self::getFiles();
        } catch (RuntimeException $e) {
            Logger::error("Error reading controller files: " . $e->getMessage());
            return [];
        }


        foreach ($files as $filePath) {
            $className = str_replace('.php', '', $filePath);
            $fullClassName = self::getNamespace() . '\\' . $className;
            if (!class_exists($fullClassName)) {
                Logger::warning("Class $fullClassName does not exist.");
                continue;
            }

            $controller = new ReflectionClass($fullClassName);
            $methods = self::getControllerMethods($controller);

            foreach ($methods as $method) {
                try {
                    $route = $method->getAttributes(Route::class);
                    $middlewares = [];
                    foreach (self::getRouteMiddleware($method) as $middleware) {
                        $middlewares[] = $middleware->newInstance();
                    }
                    if (empty($route)) {
                        continue;
                    }
                    $route = $route[array_key_first($route)]->newInstance();
                    $route->setController($controller->newInstance());
                    $services = self::getMethodServices($method);
                    $route->setParameters($services);
                    $route->setFn($method->getName());
                    $route->setMiddlewares($middlewares);
                    $routes[] = $route;
                } catch (Exception $e) {
                    Logger::error("Error getting route for method {$method->getName()} in class $fullClassName: " . $e->getMessage());
                    continue;
                }
            }
        }
        return $routes;
    }


    /**
     * Récupère les méthodes d'un contrôleur.
     *
     * @param ReflectionClass $controller La classe de contrôleur.
     * @return array Un tableau des méthodes de la classe.
     */
    private static function getControllerMethods(ReflectionClass $controller): array
    {
        return $controller->getMethods();
    }

    /**
     * Récupère les attributs supplémentaires d'une route.
     *
     * @param ReflectionMethod $method La méthode.
     * @return array Un tableau des attributs de route.
     */
    private static function getRouteMiddleware(ReflectionMethod $method): array
    {
        return array_filter(
            $method->getAttributes(),
            fn($attr) => is_subclass_of($attr->getName(), RouteMiddlewareAnnotation::class)
        );
    }


    /**
     * Récupère les services d'une méthode.
     *
     * @param ReflectionMethod $method La méthode.
     * @return array Un tableau des instances de services.
     * @throws ReflectionException Si l'initialisation de la réflexion échoue.
     */
    private static function getMethodServices(ReflectionMethod $method): array
    {
        $services = [];
        foreach ($method->getParameters() as $parameter) {
            $parameterType = $parameter->getType();
            if ($parameterType === null) {
                Logger::warning("No type for parameter {$parameter->getName()} in method {$method->getName()}.");
                continue;
            }
            try {
                $class = new ReflectionClass($parameterType->getName());
            } catch (ReflectionException $e) {
                Logger::error("Error reflecting parameter type {$parameterType->getName()} for parameter {$parameter->getName()} in method {$method->getName()}: " . $e->getMessage());
                throw $e;
            }
            if ($class->isSubclassOf(AbstractRepository::class)) {
                $services[] = Container::getInstance()->get("repositories")[$parameterType->getName()];
            }
            if ($class->isSubclassOf(ServiceInterface::class)) {
                $services[] = Container::getInstance()->get("services")[$parameterType->getName()];
            }
            if ($class->implementsInterface(ServerRequestInterface::class)) {
                $services[$parameter->getName()] = ServerRequestInterface::class;
            }
        }
        return $services;
    }
}
