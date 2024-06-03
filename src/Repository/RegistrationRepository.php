<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class RegistrationRepository extends AbstractRepository
{
    protected function getTableName(): string
    {
        return 'registration';
    }
    

}
