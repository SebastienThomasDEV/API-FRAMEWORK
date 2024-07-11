<?php

namespace Sthom\Back\Annotations;

use Sthom\Back\AbstractController;

/**
 * Classe Route pour définir les routes de l'application.
 * Cette classe est utilisée comme une annotation pour définir les routes dans les contrôleurs.
 *
 * @package Sthom\Back\Kernel\Framework\Annotations
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 * @Attributes({
 *     @Attribute("path", type="string"),
 *     @Attribute("requestType", type="string"),
 *     @Attribute("guarded", type="bool")
 * })
 * @see AbstractController
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Route
{
    const REQUEST_TYPE_GET = 'GET';
    const REQUEST_TYPE_POST = 'POST';
    const REQUEST_TYPE_PUT = 'PUT';
    const REQUEST_TYPE_DELETE = 'DELETE';
    const REQUEST_TYPE_PATCH = 'PATCH';

    private static array $validRequestTypes = [
        self::REQUEST_TYPE_GET,
        self::REQUEST_TYPE_POST,
        self::REQUEST_TYPE_PUT,
        self::REQUEST_TYPE_DELETE,
        self::REQUEST_TYPE_PATCH,
    ];

    /**
     * Le contrôleur associé à la route.
     *
     * @var AbstractController|null
     */
    private ?AbstractController $controller = null;

    /**
     * Les paramètres de la route.
     *
     * @var array
     */
    private array $parameters = [];

    /**
     * La fonction associée à la route.
     *
     * @var string
     */
    private string $fn = '';

    /**
     * Les attributs de la route.
     *
     * @var array
     */
    private array $middlewares = [];


    /**
     * Constructeur.
     *
     * @param string $path Le chemin de la route.
     * @param string $requestType Le type de requête HTTP (GET, POST, PUT, DELETE, PATCH).
     *
     * @throws \InvalidArgumentException Si le type de requête est invalide.
     */
    public function __construct(
        private readonly string $path,
        private readonly string $method = self::REQUEST_TYPE_GET,
    )
    {
        $this->validateRequestType($method);
    }

    /**
     * Valide le type de requête HTTP.
     *
     * @param string $requestType Le type de requête HTTP.
     *
     * @throws \InvalidArgumentException Si le type de requête est invalide.
     */
    private function validateRequestType(string $requestType): void
    {
        $requestType = strtoupper($requestType);
        if (!in_array($requestType, self::$validRequestTypes)) {
            throw new \InvalidArgumentException('Invalid request method');
        }
    }

    /**
     * Récupère le chemin de la route.
     *
     * @return string Le chemin de la route.
     */
    public final function getPath(): string
    {
        return $this->path;
    }

    /**
     * Récupère le type de requête HTTP de la route.
     *
     * @return string Le type de requête HTTP de la route.
     */
    public final function getMethod(): string
    {
        return strtolower($this->method);
    }

    /**
     * Définit le contrôleur pour la route.
     *
     * @param AbstractController $controller Le contrôleur pour la route.
     */
    public final function setController(AbstractController $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Récupère le contrôleur de la route.
     *
     * @return AbstractController Le contrôleur de la route.
     */
    public final function getController(): AbstractController
    {
        return $this->controller;
    }

    /**
     * Définit les paramètres pour la route.
     *
     * @param array $parameters Les paramètres pour la route.
     */
    public final function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Récupère les paramètres de la route.
     *
     * @return array Les paramètres de la route.
     */
    public final function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Définit la fonction pour la route.
     *
     * @param string $fn La fonction pour la route.
     */
    public final function setFn(string $fn): void
    {
        $this->fn = $fn;
    }

    /**
     * Récupère la fonction de la route.
     *
     * @return string La fonction de la route.
     */
    public final function getFn(): string
    {
        return $this->fn;
    }

    /**
     * Définit les middlewares pour la route.
     *
     * @param array $middlewares Les middlewares pour la route.
     */
    public final function setMiddlewares(array $middlewares): void {
        $this->middlewares = $middlewares;
    }

    /**
     * Récupère les middlewares de la route.
     *
     * @return array Les middlewares de la route.
     */
    public final function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}
