<?php

namespace Repository;

use Sthom\Back\Errors\AbstractRepository;

class TechnicalSheetRepository extends AbstractRepository
{


    public function getVelomobileByWeight(int $weight)
    {
        $query = $this->customQuery()
            ->select($this->getEntityTable())
            ->where('weight', '=', $weight)
            ->execute();
    }
    

}
