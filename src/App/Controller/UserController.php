<?php

namespace Sthom\Back\App\Controller;

use Sthom\Back\App\Entity\User;
use Sthom\Back\App\Repository\UserRepository;
use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Routing\Route;
use Sthom\Back\Kernel\Framework\Services\JwtManager;
use Sthom\Back\Kernel\Framework\Services\PasswordHasher;
use Sthom\Back\Kernel\Framework\Services\Request;

class UserController extends AbstractController
{
    #[Route(path: '/login', requestType: 'POST', guarded: false)]
    public final function login(UserRepository $userRepository, JwtManager $jwtManager, Request $request): array
    {
        $data = $request->getBody();
        $user = $userRepository->findOneBy("email", $data['login']);
        if ($user && password_verify($data['pwd'], $user->getPwd())) {
            $payload = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
            $token = $jwtManager->generate($payload);
            return $this->send([
                'token' => $token,
            ]);
        }
        return $this->send([
            'error' => 'Invalid credentials',
        ]);
    }

    #[Route(path: '/register', requestType: 'POST', guarded: false)]
    public final function register(UserRepository $userRepository, PasswordHasher $hasher, Request $request): array
    {
        $data = $request->getBody();
        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles('ROLE_USER');
        $user->setSurname($data['surname']);
        $user->setName($data['name']);
        $user->setPwd($hasher->hash($data['pwd']));
        $userRepository->save($user);
        return $this->send([
            'message' => 'User created',
        ]);
    }

    #[Route(path: '/root', requestType: 'GET', guarded: false)]
    public final function createAdmin(UserRepository $userRepository, PasswordHasher $hasher): array
    {
        $user = new User();
        $user->setEmail('root@dev.com');
        $user->setRoles('ROLE_ADMIN');
        $user->setSurname('admin');
        $user->setName('root');
        $user->setPwd($hasher->hash('root'));
        $userRepository->save($user);
        return $this->send([
            'message' => 'Admin created',
        ]);
    }

    #[Route(path: '/users', requestType: 'GET', guarded: false)]
    public final function showAll(UserRepository $userRepository): array
    {
        $users = $userRepository->findAll();
        return $this->send($users);
    }

    #[Route(path: '/users/{id}', requestType: 'GET', guarded: true)]
    public final function show(UserRepository $userRepository, Request $request): array
    {
        $id = $request->getAttribute('id');
        $user = $userRepository->find($id);
        return $this->send($user);
    }

    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public final function index(UserRepository $userRepository): array
    {
        dd($userRepository->findAll());
        return $this->send([
            'message' => 'Welcome to the user controller',
        ]);
    }


}
