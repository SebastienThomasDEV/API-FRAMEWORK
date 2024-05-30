<?php

namespace Sthom\Back\Kernel;

use Dotenv\Dotenv;
use Exception;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\Factory\AppFactory;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Middlewares\JwtMiddleware;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Kernel\Framework\Utils\ClassReader;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;
use Throwable;

/**
 * Classe Kernel
 *
 * Cette classe est le cœur de l'application,
 * responsable de l'initialisation des services et du démarrage de l'application.
 *
 * @package Sthom\Back\Kernel
 */
class Kernel
{
    use SingletonTrait;

    const ENV_FILE_PATH = __DIR__ . '/../../';
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    private \Slim\App $app;

    /**
     * Méthode principale pour démarrer l'application.
     *
     * Cette méthode configure l'environnement,
     * initialise les services,
     * définit les routes et démarre l'application Slim.
     *
     * @return void
     */
    public final function run(): void
    {
        try {
            $env = $this->initializeEnvironment();
            $this->initializeModel($env);
            $this->initializeApp();
            $this->configureMiddlewares();
            $this->configureRoutes();
            $this->app->run();
        } catch (Exception|Throwable $e) {
            $this->handleFatalError($e);
        }
    }

    /**
     * Initialise l'environnement de l'application.
     *
     * @return void
     */
    private function initializeEnvironment(): array
    {
        $dotenv = Dotenv::createImmutable(self::ENV_FILE_PATH);
        return $dotenv->load();
    }

    /**
     * Initialise le modèle de l'application.
     *  @param array $env
     * @return void
     */
    private function initializeModel(array $env): void
    {
        $model = Model::getInstance();
        $model->config($env);
    }

    /**
     * Initialise l'application Slim.
     *
     * @return void
     */
    private function initializeApp(): void
    {
        $this->app = AppFactory::create();
        $isDevMode = $_ENV['APP_ENV'] === self::ENV_DEV;
        $this->app->addErrorMiddleware($isDevMode, $isDevMode, $isDevMode);
    }

    /**
     * Configure les middlewares de l'application.
     *
     * @return void
     */
    private function configureMiddlewares(): void
    {
        $this->app->add(new JwtMiddleware());
        $this->app->options('/{routes:.+}', function (ServerRequest $serverRequest, ServerResponse $response) {
            return $response;
        });
    }

    /**
     * Configure les routes de l'application.
     *
     * @return void
     */
    private function configureRoutes(): void
    {
        $routes = ClassReader::readRoutes();
        $this->app->add(new JwtMiddleware($routes));
        foreach ($routes as $route) {
            $this->configureRoute($route);
        }
    }

    /**
     * Configure une route spécifique.
     *
     * @param Route $route La route à configurer.
     * @return void
     */
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
                return $this->addCorsHeaders($serverResponse);
            } catch (Exception|Throwable $e) {
                return $this->handleError($serverResponse, $e);
            }
        });
    }

    /**
     * Ajoute les en-têtes CORS à la réponse.
     *
     * @param ServerResponse $response
     * @return ServerResponse
     */
    private function addCorsHeaders(ServerResponse $response): ServerResponse
    {
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withHeader('Access-Control-Max-Age', '86400');
    }

    /**
     * Gestion des erreurs pour les routes.
     *
     * @param ServerResponse $response La réponse serveur.
     * @param Throwable $exception L'exception capturée.
     * @return ServerResponse La réponse serveur avec l'erreur.
     */
    private function handleError(ServerResponse $response, Throwable $exception): ServerResponse
    {
        $error = [
            'error' => true,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];
        $response->getBody()->write(json_encode($error));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    /**
     * Gestion des erreurs fatales.
     * Cette méthode est appelée en cas d'erreur fatale dans le code.
     *
     * @param Throwable $exception L'exception capturée.
     */
    private function handleFatalError(Throwable $exception): void
    {
        error_log($exception->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $_ENV['APP_ENV'] === self::ENV_DEV ? $exception->getMessage() : 'An error occurred',
        ]);
        exit;
    }
}
