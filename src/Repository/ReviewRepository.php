<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class ReviewRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'review';
    }


}
