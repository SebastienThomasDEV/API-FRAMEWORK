<?php

namespace Sthom\Back;

use Dotenv\Dotenv;
use Sthom\Back\Errors\ExceptionManager;
use Sthom\Back\Errors\Logger;

class Config
{

    const ENV_FILE_PATH = __DIR__ . '/../';
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    const configKeys = [
        'ENABLE_JWT',
        'CONTROLLER_DIR',
        'APP_ENV',
        'DB_URL'];

    public static function initializeEnvironment(): array
    {
        try {
            $dotenv = Dotenv::createImmutable(self::ENV_FILE_PATH);
            return self::validateEnvironment($dotenv->load());
        } catch (\Exception $e) {
            Logger::error($e->getMessage());
            ExceptionManager::handleFatalError($e);
        }
    }

    /**
     * @throws \Exception
     */
    private static function validateEnvironment(array $env): array
    {
        $nonValidKeys = [];
        foreach (self::configKeys as $key) {
            if (!array_key_exists($key, $env)) {
                $nonValidKeys[] = $key;
            }
        }
        if (!empty($nonValidKeys)) {
            $message = 'Missing keys in .env file: ' . implode(', ', $nonValidKeys);
            Logger::error($message);
            ExceptionManager::handleFatalError(new \Exception($message));
        }
        if ($env['APP_ENV'] !== self::ENV_DEV && $env['APP_ENV'] !== self::ENV_PROD) {
            $message = 'Invalid APP_ENV value in .env file : ' . $env['APP_ENV'] . ' must be ' . self::ENV_DEV . ' or ' . self::ENV_PROD;
            Logger::error($message);
            ExceptionManager::handleFatalError(new \Exception($message));
        }

        if (!filter_var($env['ENABLE_JWT'], FILTER_VALIDATE_BOOLEAN)) {
            Logger::error('Invalid ENABLE_JWT value in .env file');
            ExceptionManager::handleFatalError(new \Exception('Invalid ENABLE_JWT value'));
        }

        if (!filter_var($env['DB_URL'], FILTER_VALIDATE_URL)) {
            Logger::error('Invalid DB_URL value in .env file');
            ExceptionManager::handleFatalError(new \Exception('Invalid DB_URL value'));
        }
        return $env;
    }

}