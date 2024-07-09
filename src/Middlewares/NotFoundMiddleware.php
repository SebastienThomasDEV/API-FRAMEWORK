<?php

namespace Sthom\Back\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sthom\Back\Errors\ExceptionManager;

readonly class NotFoundMiddleware implements MiddlewareInterface
{
    public function __construct(private array $routes = []){}

    public final function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $routesPath = array_map(fn($route) => $route->path, $this->routes);
            $requestPath = $request->getUri()->getPath();
            if (!in_array($requestPath, $routesPath) && $requestPath !== '/') {
                throw new \Exception("Route not found");
            } else {
                return $handler->handle($request);
            }
        } catch (\Exception $e) {
            ExceptionManager::handleError($e);
        }
    }

}