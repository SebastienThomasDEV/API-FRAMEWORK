<?php

namespace Sthom\Back\App\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class ResellerRepository extends AbstractRepository
{

    protected function getTableName(): string
    {
        return 'reseller';
    }

}
