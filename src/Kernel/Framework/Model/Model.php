<?php

namespace Sthom\Back\Kernel\Framework\Model;

use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;
use PDO;
use PDOException;

/**
 * Classe Model
 *
 * Fournit des fonctionnalités de base pour configurer une connexion à la base de données et construire des requêtes SQL.
 *
 * @see SingletonTrait
 * @see SqlFn
 * @see SqlFnInterface
 */
class Model
{
    use SingletonTrait;

    const DB_HOST = 'DB_HOST';
    const DB_NAME = 'DB_NAME';
    const DB_USER = 'DB_USER';
    const DB_PASS = 'DB_PASS';

    private PDO $connection;
    /**
     * Instance de SqlFn pour exécuter les requêtes SQL.
     *
     * @var SqlFn
     */
    private SqlFn $functions;

    /**
     * Configure la connexion à la base de données.
     *
     * @param array $config Tableau contenant les paramètres de configuration de la base de données.
     *
     * @throws \Exception Si les paramètres de configuration de la base de données sont manquants.
     */
    public final function config(array $config): void
    {
        $this->validateConfig($config);
        $this->connection = $this->createConnection($config);
        $this->functions = new SqlFn($this->connection);
    }

    /**
     * Valide la configuration de la base de données.
     *
     * @param array $config Tableau contenant les paramètres de configuration de la base de données.
     *
     * @throws \Exception Si les paramètres de configuration de la base de données sont manquants.
     */
    private function validateConfig(array $config): void
    {
        $requiredParams = [self::DB_HOST, self::DB_NAME, self::DB_USER, self::DB_PASS];
        foreach ($requiredParams as $param) {
            if (!array_key_exists($param, $config)) {
                throw new \Exception('Database configuration error: missing ' . $param);
            }
        }
    }

    /**
     * Crée une connexion PDO à la base de données.
     *
     * @param array $config Tableau contenant les paramètres de configuration de la base de données.
     * @return PDO La connexion PDO.
     *
     * @throws \PDOException Si une erreur survient lors de la connexion à la base de données.
     */
    private function createConnection(array $config): PDO
    {
        try {
            $dsn = "mysql:host={$config[self::DB_HOST]};dbname={$config[self::DB_NAME]}";
            $connection = new PDO($dsn, $config[self::DB_USER], $config[self::DB_PASS]);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            throw new \PDOException('Database connection error: ' . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance de SqlFn pour construire des requêtes SQL.
     *
     * @return SqlFn Instance de SqlFn.
     */
    public final function buildQuery(): SqlFn
    {
        return $this->functions;
    }

    /**
     * Retourne la connexion PDO à la base de données.
     *
     * @return PDO La connexion PDO.
     */
    public final function getConnection(): PDO
    {
        return $this->connection;
    }

}
