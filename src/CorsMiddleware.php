<?php

namespace Sthom\Back;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

/**
 * Cette classe est un middleware
 * Elle definit un middleware qui permet de gérer les requêtes CORS
 * Elle implémente l'interface {@see MiddlewareInterface} de PSR-15 et définit la méthode {@see process()}
 * qui permet de traiter une requête HTTP
 *
 * Ce middleware est globalement appliqué à toutes les routes de l'application
 *
 * @package Sthom\Back
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Tableau des origines autorisées
     *
     * @var array
     */
    private array $allowedOrigins;

    /**
     * Constructeur de la classe CorsMiddleware
     *
     * @param array $allowedOrigins
     */
    public function __construct(array $allowedOrigins)
    {
        $this->allowedOrigins = $allowedOrigins;
    }


    /**
     * Cette méthode permet de traiter une requête HTTP reçue par l'application et lui applique les règles CORS
     *
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public final function process(Request $request, RequestHandler $handler): Response
    {
        $response = new SlimResponse();
        $origin = $request->getHeaderLine('Origin');

        if (in_array($origin, $this->allowedOrigins, true)) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->withHeader('Access-Control-Max-Age', '86400')
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Content-Type', 'application/json');

            if ($request->getMethod() === 'OPTIONS') {
                return $response;
            }
        }

        return $handler->handle($request)->withHeader('Access-Control-Allow-Origin', $origin);
    }
}
