<?php

namespace Sthom\Back\Kernel;

use Dotenv\Dotenv;
use Exception;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\Factory\AppFactory;
use Sthom\Back\Kernel\Framework\Annotations\Routing\Route;
use Sthom\Back\Kernel\Framework\Middlewares\CorsMiddleware;
use Sthom\Back\Kernel\Framework\Middlewares\JwtMiddleware;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Kernel\Framework\Utils\ControllerReader;
use Sthom\Back\Kernel\Framework\Utils\Logger;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;
use Throwable;

class Kernel
{
    use SingletonTrait;

    const ENV_FILE_PATH = __DIR__ . '/../../';
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    private \Slim\App $app;

    public final function run(): void
    {
        try {
            $env = $this->initializeEnvironment();
            $this->initializeModel($env);
            $this->initializeApp();
            $this->configureMiddlewares($env);
            $this->configureRoutes();
            $this->app->run();
        } catch (Exception|Throwable $e) {
            $this->handleFatalError($e);
        }
    }

    private function initializeEnvironment(): array
    {
        try {
            $dotenv = Dotenv::createImmutable(self::ENV_FILE_PATH);
            return $dotenv->load();
        } catch (Exception $e) {
            $this->handleFatalError($e);
            return [];
        }
    }


    private function initializeModel(array $env): void
    {
        try {
            $model = Model::getInstance();
            $model->config($env);
        } catch (Exception $e) {
            $this->handleFatalError($e);
        }
    }

    private function initializeApp(): void
    {
        $this->app = AppFactory::create();
        $isDevMode = $_ENV['APP_ENV'] === self::ENV_DEV;
        $this->app->addErrorMiddleware($isDevMode, $isDevMode, $isDevMode);
        $this->addOptionsRoute();
    }

    private function configureMiddlewares(array $env): void
    {
        $allowedOrigins = explode(',', $env['ALLOWED_ORIGINS']);
        $this->app->add(new CorsMiddleware($allowedOrigins));
        if (filter_var($env['ENABLE_JWT'], FILTER_VALIDATE_BOOLEAN)) {
            $this->app->add(new JwtMiddleware());
        }
        $this->app->addRoutingMiddleware();
    }

    private function configureRoutes(): void
    {
        ControllerReader::init( 'Sthom\Back\App\Controller\\', __DIR__ . '/../App/Controller/');
        $routes = ControllerReader::readRoutes();
        $this->app->add(new JwtMiddleware($routes));
        foreach ($routes as $route) {
            $this->configureRoute($route);
        }
    }


    private function configureRoute(Route $route): void
    {
        $requestType = $route->getRequestType();
        $path = $route->getPath();
        $this->app->{$requestType}($path, function (ServerRequest $serverRequest, ServerResponse $serverResponse) use ($route) {
            try {
                Request::getInstance()->configure($serverRequest);
                if ($serverRequest->getAttribute('error')) {
                    $serverResponse->getBody()->write(json_encode(['error' => true, 'message' => $serverRequest->getAttribute('error')]));
                } else {
                    $controller = $route->getController();
                    $response = $controller->{$route->getFn()}(...$route->getParameters());
                    $serverResponse->getBody()->write(json_encode($response));
                }
                return $serverResponse->withHeader('Content-Type', 'application/json');
            } catch (Exception|Throwable $e) {
                return $this->handleError($serverResponse, $e);
            }
        });
    }

    public final function addOptionsRoute(): void
    {
        $this->app->options('/{routes:.+}', function (ServerRequest $serverRequest, ServerResponse $response) {
            return $response;
        });
    }

    private function handleError(ServerResponse $response, Throwable $exception): ServerResponse
    {
        $error = [
            'error' => true,
            'message' => $_ENV['APP_ENV'] === self::ENV_DEV ? $exception->getMessage() : 'An error occurred',
            'code' => $exception->getCode(),
            'trace' => $_ENV['APP_ENV'] === self::ENV_DEV ? $exception->getTraceAsString() : '',
        ];
        Logger::error($exception->getMessage());
        $response->getBody()->write(json_encode($error));
        return $response->withStatus(500);
    }

    private function handleFatalError(Throwable $exception): void
    {
        Logger::error($exception->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $_ENV['APP_ENV'] === self::ENV_DEV ? $exception->getMessage() : 'An error occurred',
        ]);
        exit;
    }

}
