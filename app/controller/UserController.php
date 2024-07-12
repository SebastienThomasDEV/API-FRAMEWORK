<?php

namespace Sthom\Back\controller;

use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\middlewares\Required;
use Sthom\Back\Annotations\Route;
use Sthom\Back\repository\UserRepository;

class UserController extends AbstractController
{

    #[Route(path: '/', method: 'GET')]
    public final function index(UserRepository $repository): array
    {
        $user = $repository->findAll();
        return $this->send([
            'message' => $user
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
