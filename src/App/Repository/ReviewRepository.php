<?php

namespace Sthom\Back\App\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class ReviewRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'review';
    }


}
