<?php

namespace Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ManyToMany extends Relation
{

    public final function getSql(string $table, string $targetTable): string
    {
        dd($this->getColumnName(), $this->getTargetEntity());
    }

}