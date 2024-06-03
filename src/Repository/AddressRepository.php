<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class AddressRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'address';
    }

}
