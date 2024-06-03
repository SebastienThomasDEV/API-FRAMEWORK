<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class UserRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'user';
    }

}
