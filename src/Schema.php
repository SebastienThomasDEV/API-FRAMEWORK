<?php

namespace Sthom\Back;

use Opis\Database\Database;
use Opis\Database\Schema\CreateTable;
use Sthom\Back\Annotations\db\AbstractColumn;
use Sthom\Back\Annotations\db\ColumnType;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Utils\Logger;

class Schema
{
    public function __construct(private readonly array $schema)
    {
    }

    public final function build(Database $db): void
    {
        foreach ($this->getSchema() as $tableConfig) {
            $tableName = $tableConfig['table']->getName();
            $columns = $tableConfig['properties'];
            try {
                $db->schema()->create($tableName, function (CreateTable $tableSchema) use ($columns) {
                    foreach ($columns as $columnConfig) {
                        $this->addColumn($tableSchema, $columnConfig);
                    }
                });
            } catch (\Exception $e) {
                Logger::error($e->getMessage());
            }
        }
    }

    private function addColumn(CreateTable $tableSchema, AbstractColumn $columnConfig): void
    {
        $columnName = $columnConfig->getName();
        $columnType = $columnConfig->getType();
        $columnLength = $columnConfig->getLength();
        $method = ColumnType::match($columnType);
        if ($method) {
            $column = $tableSchema->$method($columnName);

            // Handle length for string types
            if ($method === 'string' && isset($columnLength)) {
                $column->length($columnLength);
            }

            // Handle nullable
            if (isset($columnConfig->nullable) && $columnConfig->nullable) {
                $column->nullable();
            }

            // Handle unique
            if (isset($columnConfig->unique) && $columnConfig->unique) {
                $column->unique();
            }

            // Handle default for decimal type
            if ($method === 'decimal' && isset($columnConfig->length)) {
                $column->precision($columnConfig->length);
            }

            // Handle primary key
            if ($columnConfig instanceof PrimaryKey) {
                $column->primary();
            }
        } else {
            throw new \InvalidArgumentException("Unsupported column type: $columnType");
        }
    }

    private function getSchema(): array
    {
        return $this->schema;
    }
}
