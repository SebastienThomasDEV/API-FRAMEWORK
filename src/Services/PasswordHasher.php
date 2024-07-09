<?php

namespace Sthom\Back\Services;

use Sthom\Back\Utils\ServiceInterface;
use Sthom\Back\Utils\SingletonTrait;

/**
 * Classe PasswordHasher
 *
 * Fournit des méthodes pour hasher et vérifier des mots de passe.
 */
class PasswordHasher implements ServiceInterface
{
    private bool $initialized = false;
    use SingletonTrait;

    const ALGO_BCRYPT = PASSWORD_BCRYPT;
    const ALGO_ARGON2I = PASSWORD_ARGON2I;
    const ALGO_ARGON2ID = PASSWORD_ARGON2ID;

    /**
     * Hache un mot de passe avec l'algorithme spécifié.
     *
     * @param string $password Le mot de passe à hasher.
     * @param string $algo L'algorithme de hashage (par défaut, BCRYPT).
     * @return string Le mot de passe hashé.
     */
    public final function hash(string $password, string $algo = self::ALGO_BCRYPT): string
    {
        return password_hash($password, $algo);
    }

    /**
     * Vérifie un mot de passe en clair par rapport à un hash.
     *
     * @param string $password Le mot de passe en clair.
     * @param string $hash Le hash du mot de passe.
     * @return bool Vrai si le mot de passe correspond au hash, faux sinon.
     */
    public final function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Initialise le service.
     *
     * @param array $config Configuration nécessaire pour initialiser le service.
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->initialized = true;
    }

    /**
     * Vérifie si le service est correctement initialisé.
     *
     * @return bool True si le service est initialisé, false sinon.
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }
}
