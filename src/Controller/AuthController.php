<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Entity\User;
use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\JwtManager;
use Sthom\Back\Kernel\Framework\Services\PasswordHasher;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Repository\UserRepository;

class AuthController extends AbstractController
{
    #[Route(path: '/login', requestType: 'POST', guarded: false)]
    public final function login(UserRepository $userRepository, JwtManager $jwtManager, Request $request): array
    {
        $data = $request->getBody();
        $user = $userRepository->findBy("email", $data['email']);
        if (!empty($user) && password_verify($data['mdp'], $user['mdp'])) {
            $payload = [
                'id' => $user['id'],
                'email' => $user['email'],
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
        $user->setMdp($hasher->hash($data['mdp']));
        $user->setRoles('ROLE_USER');
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $userRepository->save($user);
        return $this->send([
            'message' => 'User created',
        ]);
    }

}
