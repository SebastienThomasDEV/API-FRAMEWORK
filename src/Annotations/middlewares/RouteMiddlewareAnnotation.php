<?php

namespace Sthom\Back\Annotations\middlewares;

use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\App;

interface RouteMiddlewareAnnotation
{

    static function trigger(App $app, ServerRequest $serverRequest, ServerResponse $serverResponse): void;

}