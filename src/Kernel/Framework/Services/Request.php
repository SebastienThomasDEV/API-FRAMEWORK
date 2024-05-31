<?php

namespace Sthom\Back\Kernel\Framework\Services;

use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Sthom\Back\Kernel\Framework\Utils\ServiceInterface;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

/**
 * Classe Request
 *
 * Gère et configure les détails de la requête HTTP.
 */
class Request implements ServiceInterface
{
    use SingletonTrait;

    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';
    const HTTP_METHOD_PATCH = 'PATCH';

    private bool $configured = false;
    private array $body = [];
    private array $query = [];
    private array $attrs = [];
    private array $files = [];
    private array $headers = [];
    private string $method = '';
    private string $path = '';

    /**
     * Configure les détails de la requête à partir d'un objet ServerRequest.
     *
     * @param ServerRequest $request La requête serveur PSR-7.
     */
    public final function configure(ServerRequest $request): void
    {
        if ($this->configured) {
            return;
        }
        $this->body = $this->parseBody($request);
        $this->query = $request->getQueryParams() ?? [];
        $this->attrs = $this->filterAttributes($request->getAttributes());
        $this->files = $request->getUploadedFiles() ?? [];
        $this->headers = $request->getHeaders() ?? [];
        $this->method = $request->getMethod() ?? '';
        $this->path = $request->getUri()->getPath() ?? '';
        $this->configured = true;
    }

    /**
     * Parse le corps de la requête.
     *
     * @param ServerRequest $request La requête serveur PSR-7.
     * @return array Le corps de la requête sous forme de tableau.
     */
    private function parseBody(ServerRequest $request): array
    {
        $request->getBody()->rewind();
        return json_decode($request->getBody()->getContents(), true) ?? [];
    }

    /**
     * Filtre les attributs pour ne pas inclure ceux contenant '__'.
     *
     * @param array $attributes Les attributs de la requête.
     * @return array Les attributs filtrés.
     */
    private function filterAttributes(array $attributes): array
    {
        return array_filter($attributes, fn($key) => !str_contains($key, '__'), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Récupère le corps de la requête.
     *
     * @return array Le corps de la requête.
     */
    public final function getBody(): array
    {
        return $this->body;
    }

    /**
     * Récupère les paramètres de la requête.
     *
     * @return array Les paramètres de la requête.
     */
    public final function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Récupère les attributs de la requête.
     *
     * @return array Les attributs de la requête.
     */
    public final function getAttributes(): array
    {
        return $this->attrs;
    }

    /**
     * Récupère un attribut spécifique de la requête.
     *
     * @param string $key La clé de l'attribut.
     * @return mixed La valeur de l'attribut.
     */
    public final function getAttribute(string $key): mixed
    {
        return $this->attrs[$key] ?? null;
    }

    /**
     * Récupère les fichiers uploadés de la requête.
     *
     * @return array Les fichiers uploadés.
     */
    public final function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Récupère les en-têtes de la requête.
     *
     * @return array Les en-têtes de la requête.
     */
    public final function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Récupère la méthode HTTP de la requête.
     *
     * @return string La méthode HTTP de la requête.
     */
    public final function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Récupère le chemin de la requête.
     *
     * @return string Le chemin de la requête.
     */
    public final function getPath(): string
    {
        return $this->path;
    }

    /**
     * Initialise le service.
     *
     * @param array $config Configuration nécessaire pour initialiser le service.
     * @return void
     */
    public final function initialize(array $config): void{}

    /**
     * Vérifie si le service est correctement initialisé.
     *
     * @return bool True si le service est initialisé, false sinon.
     */
    public  final function isInitialized(): bool
    {
        return $this->configured;
    }
}
