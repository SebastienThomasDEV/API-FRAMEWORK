<?php

namespace Sthom\Admin;

use Sthom\Admin\exceptions\RouterException;

class Router
{
    private array $routes = []; // Contiendra la liste des routes

    public function __construct(private string $url){
    }


    public final function get(string $path, callable $callable) : Route{
        $route = new Route($path, $callable);
        $this->routes["GET"][] = $route;
        return $route; // On retourne la route pour "enchainer" les méthodes
    }

    public final function post(string $path, callable $callable) : Route{
        $route = new Route($path, $callable);
        $this->routes["POST"][] = $route;
        return $route; // On retourne la route pour "enchainer" les méthodes
    }

    /**
     * @throws RouterException
     */
    public final function run(): null|callable{
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        header('location: /');
        return null;
    }
}