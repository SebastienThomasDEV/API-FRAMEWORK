<?php

namespace Sthom\Back\Utils;

use RegexIterator;
use Sthom\Back\Annotations\Methods\MethodInterface;
use Sthom\Back\Annotations\Methods\Route;

/**
 * Classe abstraite ClassReader
 *
 * Fournit une méthode pour lire et récupérer les routes définies dans les contrôleurs.
 */
abstract class ControllerReader
{
    public static function getClassFromFile(string $filePath): array|false|string
    {
        // Normalize the file path
        $normalizedPath = realpath($filePath);
        if ($normalizedPath === false) {
            throw new \Exception("Error reading file $filePath: File does not exist.");
        }

        // Determine the base directories for each namespace
        $baseDirs = [
            'Sthom\\Back\\' => realpath(__DIR__ . '/../../src/'),
            'Sthom\\App\\' => realpath(__DIR__ . '/../../app/')
        ];

        $className = null;

        // Find the correct base directory and convert the relative path to a class name
        foreach ($baseDirs as $namespace => $baseDir) {
            if ($baseDir !== false && str_starts_with($normalizedPath, $baseDir)) {
                $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $normalizedPath);
                $className = $namespace . str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $relativePath);
                break;
            }
        }

        if ($className === null || !class_exists($className)) {
            error_log("Error reading file $filePath: Class $className does not exist.");
            throw new \Exception("Error reading file $filePath: Class $className does not exist.");
        }
        return $className;
    }

    public static function readControllers(string $directory): array
    {
        $controllers = [];
        $dirIterator = new \RecursiveDirectoryIterator(__DIR__ . '/../../' . $directory);
        $iterator = new \RecursiveIteratorIterator($dirIterator);
        $phpFiles = new \RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);
        foreach ($phpFiles as $file) {
            $filePath = $file[0];
            try {
                $className = self::getClassFromFile($filePath);
                $controllers[] =  $className;
            } catch (\Exception $e) {
                // Handle exception or log the error
                error_log($e->getMessage());
            }
        }

        return $controllers;
    }


    public static function readRoutes(string $directory): array
    {
        $routes = [];
        $controllers = self::readControllers($directory);
        foreach ($controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);
            $methods = $reflectionClass->getMethods();
            foreach ($methods as $method) {
                $attributes = [];
                foreach ($method->getAttributes() as $attribute) {
                    $attributes[] = $attribute->newInstance() instanceof Route ? $attribute : null;
                }
                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $endpoint = new \stdClass();
                    $endpoint->path = $route->getPath();
                    $endpoint->controller = $controller;
                    $endpoint->action = $method->getName();
                    $endpoint->parameters = $method->getParameters();
                    $routes[] = $endpoint;
                }
            }
        }
        return $routes;
    }

    public static function readRoutesBackOffice(string $directory): array
    {
        $routes = [];
        $controllers = self::readControllers($directory);
        foreach ($controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);
            $functions = $reflectionClass->getMethods();
            foreach ($functions as $fn) {
                $methods = [];
                foreach ($fn->getAttributes() as $attribute) {
                    $methods[] = $attribute->newInstance() instanceof MethodInterface ? $attribute : null;
                }
                foreach ($methods as $attribute) {
                    $route = $attribute->newInstance();
                    $endpoint = new \stdClass();
                    $endpoint->path = $route->getPath();
                    $endpoint->controller = $reflectionClass->getShortName();
                    $endpoint->fn = $fn->getName();
                    $endpoint->description = $route->getDescription();
                    $endpoint->shortName = $route->getShortName();
                    $endpoint->parameters =  preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $route->getPath(), $matches) ? $matches[1] : [];
                    $endpoint->color = $route::COLOR;
                    if ($route->getOptions() !== []) {
                        $endpoint->options = $route->getOptions();
                    }
                    $routes[] = $endpoint;
                }
            }
        }
        return $routes;
    }
}