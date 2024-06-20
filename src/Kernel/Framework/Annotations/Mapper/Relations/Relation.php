<?php

namespace Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations;

abstract class Relation
{
    private string $table;

    private string $targetTable;
    private string $columnName;
    private string $targetEntity;

    public function __construct(string $columnName, string $targetEntity)
    {
        $this->columnName = $columnName;
        $this->targetEntity = $targetEntity;
    }

    public final function getTable(): string
    {
        return $this->table;
    }

    public final function setTable(string $table): void
    {
        $this->table = $table;
    }

    public final function getTargetTable(): string
    {
        return $this->targetTable;
    }

    public final function setTargetTable(string $targetTable): void
    {
        $this->targetTable = $targetTable;
    }

    public final function getColumnName(): string
    {
        return $this->columnName;
    }

    public final function getTargetEntity(): string
    {
        return $this->targetEntity;
    }



    abstract public function getSql(string $table, string $targetTable): string;



}