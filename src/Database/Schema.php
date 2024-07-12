<?php

namespace Sthom\Back\Database;

use Opis\Database\Database;
use Opis\Database\Schema\CreateTable;
use Sthom\Back\Annotations\db\AbstractColumn;
use Sthom\Back\Annotations\db\ColumnType;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Utils\Logger;

/**
 * Class Schema
 *
 * Cette classe permet de construire le schéma de la base de données
 * Elle prend en paramètre un tableau de configuration de schéma qui est récupéré depuis les annotations
 *
 *
 * @see CreateTable
 * @see Database
 *
 * @package Sthom\Back\Database
 */
class Schema
{

    /**
     * Constructeur de la classe Schema qui prend en paramètre le schéma de la base de données
     *
     * @param array $schema
     */
    public function __construct(private readonly array $schema)
    {
    }

    /**
     * Cette méthode permet de construire le schéma de la base de données
     *
     * @param Database $db
     * @return void
     */
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

    /**
     * Cette méthode permet d'ajouter une colonne à une table
     *
     * @param CreateTable $tableSchema
     * @param AbstractColumn $columnConfig
     * @return void
     */
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

            if (isset($columnConfig->nullable)) {
                if (!$columnConfig->nullable) {
                    $column->notNull();
                }
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
                $column->primary()->autoincrement();
            }
        } else {
            throw new \InvalidArgumentException("Unsupported column type: $columnType");
        }
    }

    /**
     * Cette méthode permet de récupérer le schéma de la base de données
     *
     * @return array
     */
    private function getSchema(): array
    {
        return $this->schema;
    }
}
