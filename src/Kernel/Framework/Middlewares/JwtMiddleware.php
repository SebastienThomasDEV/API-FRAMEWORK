<?php

namespace Sthom\Back\Kernel\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Services\JwtManager;

/**
 * Middleware JWT
 *
 * Vérifie la validité des tokens JWT et protège les routes annotées comme telles.
 */
class JwtMiddleware implements MiddlewareInterface
{
    private array $routes;
    private ?Route $route = null;

    const ERROR_NO_TOKEN = 'Unauthorized access: no JWT token found';
    const ERROR_INVALID_TOKEN = 'Invalid JWT token';
    const ERROR_USER_NOT_FOUND = 'User not found';
    const ERROR_USER_IDENTIFIER_NOT_FOUND = 'User identifier not found';

    /**
     * Constructeur.
     *
     * @param array $routes Les routes à protéger.
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Traite la requête et vérifie la validité du token JWT.
     *
     * @param ServerRequestInterface $request La requête serveur.
     * @param RequestHandlerInterface $handler Le gestionnaire de requêtes.
     * @return ResponseInterface La réponse serveur.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->route = $this->resolveCurrentRoute($request);
        $jwt = JwtManager::getInstance();

        if ($this->route && $this->route->isGuarded()) {
            if (!$jwt->isTokenPresent($request)) {
                return $this->handleError($request, $handler, self::ERROR_NO_TOKEN);
            }
            if (!$jwt->isValid($request)) {
                return $this->handleError($request, $handler, self::ERROR_INVALID_TOKEN);
            }
            $model = Model::getInstance();
            $identifier = $_ENV['JWT_USER_IDENTIFIER'];
            $table = $_ENV['JWT_USER_TABLE'];
            $jwtPayload = $jwt->getPayload($request);
            if (isset($jwtPayload[$identifier])) {
                $user = $model
                    ->buildQuery()
                    ->select($table)
                    ->where($identifier, '=', $jwtPayload[$identifier])
                    ->execute()[0] ?? null;
                if (!empty($user)) {
                    $request = $request->withAttribute('user', $user);
                    return $handler->handle($request);
                } else {
                    return $this->handleError($request, $handler, self::ERROR_USER_NOT_FOUND);
                }
            }
            return $this->handleError($request, $handler, self::ERROR_USER_IDENTIFIER_NOT_FOUND);
        }
        return $handler->handle($request);
    }

    /**
     * Résout la route actuelle à partir de la requête.
     *
     * @param ServerRequestInterface $request La requête serveur.
     * @return Route|null La route correspondante ou null si aucune route ne correspond.
     */
    private function resolveCurrentRoute(ServerRequestInterface $request): ?Route
    {
        $path = $request->getUri()->getPath();
        foreach ($this->routes as $route) {
            if ($route->getPath() === $path) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Gère les erreurs liées aux tokens JWT.
     *
     * @param ServerRequestInterface $request La requête serveur.
     * @param RequestHandlerInterface $handler Le gestionnaire de requêtes.
     * @param string $errorMessage Le message d'erreur.
     * @return ResponseInterface La réponse serveur avec l'erreur.
     */
    private function handleError(ServerRequestInterface $request, RequestHandlerInterface $handler, string $errorMessage): ResponseInterface
    {
        $request = $request->withAttribute('error', $errorMessage);
        return $handler->handle($request);
    }
}
