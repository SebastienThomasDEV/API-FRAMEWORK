<?php

namespace Sthom\Back\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sthom\Back\Utils\ServiceInterface;
use Sthom\Back\Utils\SingletonTrait;

/**
 * Classe JwtManager
 *
 * Gère la génération, la validation et le décodage des JWT (JSON Web Tokens).
 */
class JwtManager implements ServiceInterface
{
    private bool $initialized = false;
    use SingletonTrait;

    const ERROR_INVALID_TOKEN = 'Invalid JWT token';
    const ERROR_NO_TOKEN = 'No JWT token found';
    const ALGORITHM = 'RS256';

    const DIR = __DIR__ . '/../../../../';

    private string $privateKey;
    private string $publicKey;



    /**
     * Charge une clé depuis un fichier.
     *
     * @param string $path Le chemin du fichier de clé.
     * @return string Le contenu de la clé.
     *
     * @throws \RuntimeException Si le fichier de clé ne peut être lu.
     */
    private function loadKey(string $path): string
    {

        $key = @file_get_contents(self::DIR . $path);
        if ($key === false) {
            throw new \RuntimeException("Unable to read key from path: $path");
        }
        return $key;
    }

    /**
     * Vérifie la validité du JWT.
     *
     * @param \Sthom\Back\Services\Request $request La requête HTTP.
     * @return bool Vrai si le JWT est valide, faux sinon.
     */
    public final function isValid(Request $request): bool
    {
        $token = $this->getTokenFromRequest($request);
        if (!$token) {
            return false;
        }

        try {
            JWT::decode($token, new Key($this->publicKey, self::ALGORITHM));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Récupère le payload du JWT dans la requête.
     *
     * @param \Sthom\Back\Services\Request $request La requête HTTP.
     * @return array Le payload du JWT.
     *
     * @throws \RuntimeException Si le token est invalide.
     */
    public final function getPayload(Request $request): array
    {
        $token = $this->getTokenFromRequest($request);
        if (!$token) {
            throw new \RuntimeException(self::ERROR_NO_TOKEN);
        }

        try {
            return (array) JWT::decode($token, new Key($this->publicKey, self::ALGORITHM));
        } catch (\Exception $e) {
            throw new \RuntimeException(self::ERROR_INVALID_TOKEN);
        }
    }

    /**
     * Génère un JWT.
     *
     * @param array $data Les données à encoder dans le JWT.
     * @return string Le JWT généré.
     */
    public final function generate(array $data): string
    {
        $data['iat'] = time();
        $data['exp'] = time() + 3600;
        $data['iss'] = $_ENV['JWT_ISSUER'];
        return JWT::encode($data, $this->privateKey, self::ALGORITHM);
    }

    /**
     * Décode un JWT.
     *
     * @param string $token Le JWT à décoder.
     * @return array Les données du JWT.
     *
     * @throws \RuntimeException Si le token est invalide.
     */
    public final function decode(string $token): array
    {
        try {
            return (array) JWT::decode($token, new Key($this->publicKey, self::ALGORITHM));
        } catch (\Exception $e) {
            throw new \RuntimeException(self::ERROR_INVALID_TOKEN);
        }
    }

    /**
     * Vérifie si le JWT est présent dans la requête.
     *
     * @param \Sthom\Back\Services\Request $request La requête HTTP.
     * @return bool Vrai si le JWT est présent, faux sinon.
     */
    public final function isTokenPresent(Request $request): bool
    {
        return $this->getTokenFromRequest($request) !== null;
    }

    /**
     * Extrait le JWT de la requête.
     *
     * @param \Sthom\Back\Services\Request $request La requête HTTP.
     * @return string|null Le JWT ou null si absent.
     */
    private function getTokenFromRequest(Request $request): ?string
    {
        $authHeader = $request->getHeader('Authorization');
        if (!isset($authHeader[0])) {
            return null;
        }
        return str_replace('Bearer ', '', $authHeader[0]);
    }

    public final function initialize(array $config): void
    {
        $this->privateKey = $this->loadKey($_ENV['JWT_PRIVATE_KEY_PATH']);
        $this->publicKey = $this->loadKey($_ENV['JWT_PUBLIC_KEY_PATH']);
        $this->initialized = true;
    }

    public final function isInitialized(): bool
    {
        return $this->initialized;
    }
}
