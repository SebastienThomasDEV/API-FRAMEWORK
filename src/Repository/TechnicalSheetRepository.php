<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class TechnicalSheetRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'technical_sheet';
    }
    

}
