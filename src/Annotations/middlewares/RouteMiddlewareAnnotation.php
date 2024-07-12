<?php

namespace Sthom\Back\Annotations\middlewares;

use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\App;

/**
 * Cette interface permet de définir un middleware de type RouteMiddlewareAnnotation et générique
 * Cela permet de déclencher un middleware personnalisé et non global à l'application
 *
 * @package Sthom\Back\Annotations\middlewares
 */
interface RouteMiddlewareAnnotation
{
    /**
     * Cette méthode permet de déclencher un middleware de type RouteMiddlewareAnnotation
     * Cela permet de déclencher un middleware personnalisé et non global à l'application
     *
     * @param App $app
     * @param ServerRequest $serverRequest
     * @param ServerResponse $serverResponse
     *
     * @return bool
     */
    static function trigger(App $app, ServerRequest $serverRequest, ServerResponse $serverResponse):  bool;

}