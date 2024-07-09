<?php

namespace Sthom\Back;

use Exception;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\App;
use Slim\Factory\AppFactory;
use Sthom\Back\Errors\ExceptionManager;
use Sthom\Back\Errors\Logger;
use Sthom\Back\Middlewares\JwtMiddleware;
use Sthom\Back\Middlewares\NotFoundMiddleware;
use Sthom\Back\Model\Model;
use Sthom\Back\Utils\ControllerReader;
use Sthom\Back\Utils\SingletonTrait;
use Throwable;
use Tuupola\Middleware\CorsMiddleware;

class Kernel
{
    use SingletonTrait;

    const ENV_PROD = 'prod';

    const ENV_DEV = 'dev';

    private App $app;

    public final function run(): void
    {
        Logger::setDir("logs");
        try {
            $env = Config::initializeEnvironment();
            $this->initializeModel($env);
            $this->app = AppFactory::create();
            $routes = ControllerReader::readRoutes($env['CONTROLLER_DIR']);
            $this->configureRoutes($routes);
            $this->configureMiddlewares($env, $routes);
            $this->app->run();
        } catch (Exception|Throwable $e) {
            Logger::setDir("logs");
            ExceptionManager::handleFatalError($e);
        }
    }


    private function initializeModel(array $env): void
    {
        try {
            Model::connect($env['DB_URL']);
        } catch (Exception $e) {
            ExceptionManager::handleFatalError($e);
        }
    }


    private function configureMiddlewares(array $env, array $routes): void
    {
        $config = file_get_contents(__DIR__ . '/../api.config.json');
        $config = json_decode($config, true);
        $this->app->add(new CorsMiddleware($config['cors']));
        $this->app->add(new NotFoundMiddleware($routes));
        $this->app->options('/{routes:.+}', function (ServerRequest $serverRequest, ServerResponse $response) {
            return $response;
        });
    }


    private function configureRoutes(array $endpoints): void
    {
        foreach ($endpoints as $endpoint) {
            $this->app->{$endpoint->method}($endpoint->path, function (ServerRequest $request, ServerResponse $response, $args) use ($endpoint) {
                $controller = new $endpoint->controller();
                $parameters = [];
                foreach ($endpoint->parameters as $parameter) {
                    $parameters[] = match ($parameter->getName()) {
                        'request' => $request,
                        default => $args[$parameter->getName()] ?? null,
                    };
                }
                $data = call_user_func([$controller, $endpoint->action], ...$parameters);
                $response->getBody()->write(json_encode($data));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            });
        }
    }
}
