<?php

namespace Sthom\Back\controller;

use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ServerRequestInterface;
use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\Route;

class UserController extends AbstractController
{

    #[Route(path: '/', method: 'POST')]
    public final function index(ServerRequestInterface $request): array
    {
        $message = 'Hello World';
        return $this->send([
            'message' => $message
        ]);
    }

}
