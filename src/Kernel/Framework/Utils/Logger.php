<?php

namespace Sthom\Back\Kernel\Framework\Utils;

abstract class Logger
{

    public static function log(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $log = "$date : $message\n";
        file_put_contents(__DIR__ . '/../../../../log/app.txt', $log, FILE_APPEND);
    }

    public static function error(string $message): void
    {
        self::log("ERROR : $message");
    }

    public static function info(string $message): void
    {
        self::log("INFO : $message");
    }

    public static function warning(string $message): void
    {
        self::log("WARNING : $message");
    }

    public static function debug(string $message): void
    {
        self::log("DEBUG : $message");
    }

    public static function success(string $message): void
    {
        self::log("SUCCESS : $message");
    }

    public static function exception(\Exception $e): void
    {
        self::log("EXCEPTION : " . $e->getMessage());
    }

    public static function exceptionToString(\Exception $e): string
    {
        return "EXCEPTION : " . $e->getMessage();
    }


}