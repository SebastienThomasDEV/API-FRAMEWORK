<?php

namespace Sthom\App\controller;

use Psr\Http\Message\RequestInterface;
use Sthom\App\dto\VelomobileDto;
use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\Methods\Delete;
use Sthom\Back\Annotations\Methods\Get;
use Sthom\Back\Annotations\Methods\Post;
use Sthom\Back\Annotations\Methods\Put;

class VelomobileController extends AbstractController
{

    #[Get(path: '/velos', description: "Cette route permet de récupérer un velomobile")]
    public final function show(RequestInterface $request, string $name): array
    {
        return $this->send(['velomobiles' => 'all' ]);
    }

    #[Get(path: '/qzd', description: "Cette route permet de récupérer rien")]
    public final function boum(RequestInterface $request, string $name): array
    {
        return $this->send(['velomobiles' => 'all' ]);
    }
    #[Post(path: '/velos', description: "Cette route permet de créer un velomobile")]
    public final function post(VelomobileDto $dto): array
    {
        return $this->send(['velomobiles' => 'all' ]);
    }

    #[Put(path: '/velos/{id}', description: "Cette route permet de modifier un velomobile")]
    public final function put(int $id, VelomobileDto $dto): array
    {
        return $this->send(['velomobiles' => 'all' ]);
    }

    #[Delete(path: '/velos', description: "Cette route permet de supprimer un velomobile")]
    public final function delete(VelomobileDto $dto): array
    {
        return $this->send(['velomobiles' => 'all' ]);
    }













}