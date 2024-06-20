<?php

namespace Sthom\Back\Kernel\Framework\Model\Interfaces;

/**
 * Interface SqlFnInterface
 *
 * This interface defines the methods for SQL function handling in the application.
 */
interface SqlFnInterface
{
    /**
     * Selects fields from a table.
     *
     * @param string $table The name of the table.
     * @param array $fields The fields to select. Default is ['*'].
     * @return SqlFnInterface
     */
    public function select(string $table, array $fields = ['*']): SqlFnInterface;

    /**
     * Adds a WHERE condition to the SQL query.
     *
     * @param string $field The field name.
     * @param string $operator The operator (e.g., '=', '>', '<', etc.).
     * @param mixed $value The value to compare.
     * @return SqlFnInterface
     */
    public function where(string $field, string $operator, $value): SqlFnInterface;

    /**
     * Adds an AND WHERE condition to the SQL query.
     *
     * @param string $field The field name.
     * @param string $operator The operator (e.g., '=', '>', '<', etc.).
     * @param mixed $value The value to compare.
     * @return SqlFnInterface
     */
    public function andWhere(string $field, string $operator, $value): SqlFnInterface;

    /**
     * Adds an OR WHERE condition to the SQL query.
     *
     * @param string $field The field name.
     * @param string $operator The operator (e.g., '=', '>', '<', etc.).
     * @param mixed $value The value to compare.
     * @return SqlFnInterface
     */
    public function orWhere(string $field, string $operator, $value): SqlFnInterface;

    /**
     * Adds an ORDER BY clause to the SQL query.
     *
     * @param string $field The field name.
     * @param string $direction The direction ('ASC' or 'DESC'). Default is 'ASC'.
     * @return SqlFnInterface
     */
    public function orderBy(string $field, string $direction = 'ASC'): SqlFnInterface;

    /**
     * Adds a LIMIT clause to the SQL query.
     *
     * @param int $limit The number of records to limit.
     * @return SqlFnInterface
     */
    public function limit(int $limit): SqlFnInterface;

    /**
     * Adds an OFFSET clause to the SQL query.
     *
     * @param int $offset The number of records to offset.
     * @return SqlFnInterface
     */
    public function offset(int $offset): SqlFnInterface;

    /**
     * Executes the built SQL query and returns the results.
     *
     * @return array The query results.
     */
    public function execute(): array;

    /**
     * Inserts data into a table.
     *
     * @param string $table The table name.
     * @param array $data The data to insert (associative array).
     * @return int The last insert ID.
     */
    public function insert(string $table, array $data): int;

    /**
     * Updates data in a table.
     *
     * @param string $table The table name.
     * @param array $data The data to update (associative array).
     * @param int $id The ID of the record to update.
     * @return int The number of affected rows.
     */
    public function update(string $table, array $data, int $id): int;

    /**
     * Deletes a record from a table.
     *
     * @param string $table The table name.
     * @param int $id The ID of the record to delete.
     * @return int The number of affected rows.
     */
    public function delete(string $table, int $id): int;

    /**
     * Counts the number of records.
     *
     * @param string $field The field to count. Default is '*'.
     * @return int The count of records.
     */
    public function count(string $field = '*'): int;

    /**
     * Adds a WHERE IN condition to the SQL query.
     *
     * @param string $field The field name.
     * @param array $values The values for the IN clause.
     * @return SqlFnInterface
     */
    public function whereIn(string $field, array $values): SqlFnInterface;

    /**
     * Adds an AND WHERE IN condition to the SQL query.
     *
     * @param string $field The field name.
     * @param array $values The values for the IN clause.
     * @return SqlFnInterface
     */
    public function andWhereIn(string $field, array $values): SqlFnInterface;

    /**
     * Adds an OR WHERE IN condition to the SQL query.
     *
     * @param string $field The field name.
     * @param array $values The values for the IN clause.
     * @return SqlFnInterface
     */
    public function orWhereIn(string $field, array $values): SqlFnInterface;

    /**
     * Adds a GROUP BY clause to the SQL query.
     *
     * @param string $field The field name.
     * @return SqlFnInterface
     */
    public function groupBy(string $field): SqlFnInterface;

    /**
     * Adds a HAVING condition to the SQL query.
     *
     * @param string $field The field name.
     * @param string $operator The operator (e.g., '=', '>', '<', etc.).
     * @param mixed $value The value to compare.
     * @return SqlFnInterface
     */
    public function having(string $field, string $operator, $value): SqlFnInterface;

    /**
     * Begins a database transaction.
     *
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * Commits a database transaction.
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Rolls back a database transaction.
     *
     * @return void
     */
    public function rollBack(): void;

    /**
     * Executes a callable within a database transaction.
     *
     * @param callable $callback The callback to execute.
     * @return void
     */
    public function executeTransaction(callable $callback): void;

    /**
     * Adds a raw SQL query.
     *
     * @param string $query The raw SQL query.
     * @return SqlFnInterface
     */
    public function raw(string $query): SqlFnInterface;
}
