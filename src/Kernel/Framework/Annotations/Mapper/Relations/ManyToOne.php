<?php

namespace Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ManyToOne extends Relation
{


    public function __construct(string $columnName, string $mappedBy)
    {
        parent::__construct($columnName, $targetEntity);
    }



    public final function getSql(string $table, string $targetTable): string
    {
        dd($this->getColumnName(), $this->getTargetEntity(), $this->getReferencedColumn());
    }



}