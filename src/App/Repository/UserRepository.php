<?php

namespace Sthom\Back\App\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class UserRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'user';
    }

}