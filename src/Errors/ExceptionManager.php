<?php

namespace Sthom\Back\Errors;

use JetBrains\PhpStorm\NoReturn;

abstract class ExceptionManager
{

    #[NoReturn]
    public static function handleFatalError(\Throwable $e): void
    {

        $error = sprintf(
            self::template,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getCode(),
            implode('<br>#', explode('#', $e->getTraceAsString()))
        );
        Logger::error($e->getMessage());
        echo $error;
        exit(1);
    }


    #[NoReturn]
    public static function handleError(\Throwable $e): void
    {
        $error = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => implode("---->", explode('#', $e->getTraceAsString()))
        ];
        Logger::error($e->getMessage());
        header('Content-Type: application/json', true, 500);
        echo json_encode($error);
        exit(1);
    }

    const template = <<<HTML
    <html lang="en">
        <head>
            <title>Erreur</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                h1 {
                    background-color: #333;
                    color: #fff;
                    margin: 0;
                    padding: 10px;
                }
                p {
                    margin: 10px;
                    padding: 10px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                }
               
            </style>
        </head>
        <body>
            <h1>Fatal Error</h1>
            <p>Message : %s</p>
            <p>Fichier : %s</p>
            <p>Ligne : %s</p>
            <p>Code : %s</p>
            <p>Trace : %s</p>
        </body>
    </html>
HTML;


}