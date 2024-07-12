<?php

namespace Sthom\Back;

use Dotenv\Dotenv;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Opis\Database\Connection;
use Opis\Database\Database;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\Factory\AppFactory;
use Sthom\Back\Annotations\Route;
use Sthom\Back\Database\Schema;
use Sthom\Back\Parser\ControllerReader;
use Sthom\Back\Parser\EntityReader;
use Sthom\Back\Utils\Logger;
use Sthom\Back\Utils\SingletonTrait;
use Throwable;

class Kernel
{
    use SingletonTrait;

    const ENV_FILE_PATH = __DIR__ . '/../';
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    private \Slim\App $app;

    public final function run(): void
    {
        try {
            $env = $this->initializeEnvironment();
            $this->initializeModel($env);
            $this->initializeApp();
            $this->configureAppMiddlewares($env);
            $this->configureRoutes($env);
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
        }
    }


    private function initializeModel(array $env): void
    {
        try {
            $entityReader = new EntityReader($env['ENTITY_NAMESPACE'], __DIR__ . $env['ENTITY_DIR']);
            $connection = new Connection($env['DB_URL'], $env['DB_USER'], $env['DB_PASS']);
            $db = new Database($connection);
            $entities = $entityReader->getEntities();
            $repositories = [];
            foreach ($entities as $entity) {
                $repository = $entity['repository'];
                $repositories[$repository] = new $repository($entity['entity'], $entity['table']->getName());
            }
            Container::getInstance()->set("repositories", $repositories);
            $schema = new Schema($entities);
            $schema->build($db);
            Container::getInstance()->set(Database::class, $db);
        } catch (Exception $e) {
            $this->handleFatalError($e);
        }

    }

    private function initializeApp(): void
    {
        $this->app = AppFactory::create();
        $isDevMode = $_ENV['ENV_MODE'] === self::ENV_DEV;
        $this->app->addErrorMiddleware($isDevMode, $isDevMode, $isDevMode);
        $this->addOptionsRoute();
    }

    private function configureAppMiddlewares(array $env): void
    {
        $allowedOrigins = explode(',', $env['ALLOWED_ORIGINS']);
        $this->app->add(new CorsMiddleware($allowedOrigins));
        $this->app->addRoutingMiddleware();
    }

    private function configureRoutes(array $env): void
    {
        $controllerReader = new ControllerReader($env['CONTROLLER_NAMESPACE'], __DIR__ . $env['CONTROLLER_DIR']);
        $routes = $controllerReader->readRoutes();
        foreach ($routes as $route) {
            $this->configureRoute($route);
        }
    }


    private function configureRoute(Route $route): void
    {
        $method = $route->getMethod();
        $path = $route->getPath();
        $this->app->{$method}($path, function (ServerRequest $serverRequest, ServerResponse $serverResponse) use ($route) {
            try {
                foreach ($route->getMiddlewares() as $middleware) {
                    if (!$middleware::trigger($this->app, $serverRequest, $serverResponse)) {
                        return $serverResponse->withHeader('Content-Type', 'application/json')->withStatus(401);
                    }
                }
                $controller = $route->getController();
                $response = $controller->{$route->getFn()}(...$route->getParameters());
                $serverResponse->getBody()->write(json_encode($response));
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
        $error = array(
            'error' => true,
            'message' => $_ENV['ENV_MODE'] === self::ENV_DEV ? $exception->getMessage() : 'An error occurred',
            'code' => $exception->getCode(),
        );
        Logger::error($exception->getMessage());
        $response->getBody()->write(json_encode($error));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    #[NoReturn]
    private function handleFatalError(Throwable $exception): void
    {
        Logger::error($exception->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $exception->getMessage(),
        ]);
        exit;
    }

}
