<?php

namespace Sthom\Back\Kernel\Framework\Model;

use Sthom\Back\Kernel\Framework\Model\Exceptions\OrmException;
use Sthom\Back\Kernel\Framework\Model\Interfaces\SqlFnInterface;

class SqlFn implements SqlFnInterface
{
    const OPERATORS = ['=', '>', '<', '>=', '<=', '<>', '!=', 'LIKE'];

    private \PDO $connection;
    private string $statement;
    private array $bindParams = [];

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }



    private function validateOperator(string $operator): void
    {
        if (!in_array($operator, self::OPERATORS)) {
            throw new \InvalidArgumentException("Invalid operator: $operator");
        }
    }

    private function reset(): void
    {
        $this->statement = '';
        $this->bindParams = [];
    }

    public final function select(string $table, array $fields = ['*']): SqlFnInterface
    {
        $this->statement = "SELECT " . implode(', ', $fields) . " FROM $table";
        return $this;
    }

    public final function where(string $field, string $operator, $value): SqlFnInterface
    {
        $this->validateOperator($operator);
        $this->statement .= " WHERE $field $operator :$field";
        $this->bindParams[$field] = $value;
        return $this;
    }

    public final function andWhere(string $field, string $operator, $value): SqlFnInterface
    {
        $this->validateOperator($operator);
        $this->statement .= " AND $field $operator :$field";
        $this->bindParams[$field] = $value;
        return $this;
    }

    public final function orWhere(string $field, string $operator, $value): SqlFnInterface
    {
        $this->validateOperator($operator);
        $this->statement .= " OR $field $operator :$field";
        $this->bindParams[$field] = $value;
        return $this;
    }

    public final function orderBy(string $field, string $direction = 'ASC'): SqlFnInterface
    {
        $this->statement .= " ORDER BY $field $direction";
        return $this;
    }

    public final function limit(int $limit): SqlFnInterface
    {
        $this->statement .= " LIMIT $limit";
        return $this;
    }

    public final function offset(int $offset): SqlFnInterface
    {
        $this->statement .= " OFFSET $offset";
        return $this;
    }

    public final function execute(): array
    {
        try {
            $stmt = $this->connection->prepare($this->statement);
            $stmt->execute($this->bindParams);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new OrmException('Error executing query', 0, $e);
        } finally {
            $this->reset();
        }
    }

    public final function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $placeholders = array_map(fn($field) => ":$field", $fields);
        $this->statement = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $this->bindParams = $data;

        $stmt = $this->connection->prepare($this->statement);
        $stmt->execute($this->bindParams);
        return $this->connection->lastInsertId();
    }

    public final function update(string $table, array $data, int $id): int
    {
        $fields = array_keys($data);
        $setClause = implode(', ', array_map(fn($field) => "$field = :$field", $fields));
        $this->statement = "UPDATE $table SET $setClause WHERE id = :id";
        $data['id'] = $id;
        $this->bindParams = $data;

        $stmt = $this->connection->prepare($this->statement);
        $stmt->execute($this->bindParams);
        return $stmt->rowCount();
    }

    public final function delete(string $table, int $id): int
    {
        $this->statement = "DELETE FROM $table WHERE id = :id";
        $this->bindParams = ['id' => $id];

        $stmt = $this->connection->prepare($this->statement);
        $stmt->execute($this->bindParams);
        return $stmt->rowCount();
    }

    public final function count(): int
    {
        $stmt = $this->connection->prepare($this->statement);
        $stmt->execute($this->bindParams);
        return $stmt->rowCount();
    }

    private function addJoin(string $type, string $table, string $field1, string $operator, string $field2): SqlFnInterface
    {
        $this->validateOperator($operator);
        $this->statement .= " $type JOIN $table ON $field1 $operator $field2";
        return $this;
    }

    public final function join(string $table, string $field1, string $operator, string $field2): SqlFnInterface
    {
        return $this->addJoin('INNER', $table, $field1, $operator, $field2);
    }

    public final function leftJoin(string $table, string $field1, string $operator, string $field2): SqlFnInterface
    {
        return $this->addJoin('LEFT', $table, $field1, $operator, $field2);
    }

    public final function rightJoin(string $table, string $field1, string $operator, string $field2): SqlFnInterface
    {
        return $this->addJoin('RIGHT', $table, $field1, $operator, $field2);
    }

    public final function innerJoin(string $table, string $field1, string $operator, string $field2): SqlFnInterface
    {
        return $this->addJoin('INNER', $table, $field1, $operator, $field2);
    }

    public final function groupBy(string $field): SqlFnInterface
    {
        $this->statement .= " GROUP BY $field";
        return $this;
    }

    public final function having(string $field, string $operator, $value): SqlFnInterface
    {
        $this->validateOperator($operator);
        $this->statement .= " HAVING $field $operator :$field";
        $this->bindParams[$field] = $value;
        return $this;
    }

    public final function set(string $field, $value): SqlFnInterface
    {
        $this->statement .= " SET $field = :$field";
        $this->bindParams[$field] = $value;
        return $this;
    }

    public final function sum(string $field): SqlFnInterface
    {
        $this->statement .= " SUM($field)";
        return $this;
    }

    public final function avg(string $field): SqlFnInterface
    {
        $this->statement .= " AVG($field)";
        return $this;
    }

    public final function min(string $field): SqlFnInterface
    {
        $this->statement .= " MIN($field)";
        return $this;
    }

    public final function max(string $field): SqlFnInterface
    {
        $this->statement .= " MAX($field)";
        return $this;
    }

    public final function distinct(string $field): SqlFnInterface
    {
        $this->statement .= " DISTINCT $field";
        return $this;
    }

    public final function raw(string $query): SqlFnInterface
    {
        $this->statement = $query;
        return $this;
    }


}
