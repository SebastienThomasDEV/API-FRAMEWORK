<?php

namespace Sthom\Back\controller;

use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\Route;

class UserController extends AbstractController
{

    #[Route(path: '/', method: 'GET')]
    public final function index(): array
    {
        $message = 'Hello World';
        return $this->send([
            'message' => $message
        ]);
    }

}
