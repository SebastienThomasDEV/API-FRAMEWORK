<?php

namespace Sthom\Back\controller;

use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\middlewares\Required;
use Sthom\Back\Annotations\Route;

class UserController extends AbstractController
{

    #[Route(path: '/', method: 'GET')]
    public final function index(): array
    {

        return $this->send([
            'message' => 'Welcome to the user controller',
        ]);
    }


    #[Required(role: 'ROLE_USER')]
    #[Route(path: '/profile', method: 'GET')]
    public final function profile(): array
    {
        return $this->send([
            'message' => 'Welcome to the user profile',
        ]);
    }


}
