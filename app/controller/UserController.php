<?php

namespace Sthom\Back\controller;

use http\Env\Request;
use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ServerRequestInterface;
use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\Route;
use Sthom\Back\Service\JwtManager;

class UserController extends AbstractController
{

    #[Route(path: '/', method: 'GET')]
    public final function index(ServerRequestInterface $request, JwtManager $jwtManager): array
    {
        $message = 'Hello World';
        return $this->send([
            'message' => $message
        ]);
    }

}
