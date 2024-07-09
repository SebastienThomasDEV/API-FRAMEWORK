<?php

namespace Sthom\Back\Errors;

abstract class Logger
{

    private static string $logDir;

    public static function setDir(string $logDir): void
    {
        self::$logDir = $logDir;
    }

    public static function log(string $message, string $fileName): void
    {
        $date = date('Y-m-d H:i:s');
        $log = "$date : $message\n";
        file_put_contents(__DIR__ . "/../../".self::$logDir."/$fileName.txt", $log, FILE_APPEND);
    }

    public static function error(string $message): void
    {
        self::log("ERROR : $message", 'error');
    }

    public static function info(string $message): void
    {
        self::log("INFO : $message", 'info');
    }

    public static function warning(string $message): void
    {
        self::log("WARNING : $message", 'warning');
    }

    public static function success(string $message): void
    {
        self::log("SUCCESS : $message", 'success');
    }

    public static function sqlException(\Exception $e): void
    {
        self::log("SQL EXCEPTION : " . $e->getMessage(), 'sql');
    }


}