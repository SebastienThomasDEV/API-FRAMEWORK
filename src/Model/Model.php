<?php

namespace Sthom\Back\Model;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Tools\DsnParser;
use PDOException;
use Sthom\Back\Errors\ExceptionManager;
use Sthom\Back\Utils\SingletonTrait;

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

    /**
     * @var \Doctrine\DBAL\Connection|null
     */
    private static \Doctrine\DBAL\Connection $connection;

    public static function connect(string $url): void
    {
        $dsnParser = new DsnParser();
        $connectionParams = $dsnParser
            ->parse($url);
        try {
            try {
                self::$connection = DriverManager::getConnection($connectionParams);
            } catch (Exception $e) {
                ExceptionManager::handleFatalError($e);
            }
        } catch (PDOException $e) {
            ExceptionManager::handleFatalError($e);
        }
    }

}
