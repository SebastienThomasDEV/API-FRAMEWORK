<?php

namespace Sthom\Back\App\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class RegistrationRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'registration';
    }
    

}
