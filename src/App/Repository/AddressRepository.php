<?php

namespace Sthom\Back\App\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class AddressRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'address';
    }

}
