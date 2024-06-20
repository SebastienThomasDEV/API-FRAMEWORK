<?php

namespace Sthom\Back\Kernel\Framework\Model;

use Sthom\Back\Kernel\Framework\Model\Exceptions\InvalidOperatorException;
use Sthom\Back\Kernel\Framework\Model\Exceptions\QueryExecutionException;
use Sthom\Back\Kernel\Framework\Model\Interfaces\SqlFnInterface;
use Sthom\Back\Kernel\Framework\Utils\Logger;

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
            throw new InvalidOperatorException("Invalid operator: $operator. Allowed operators are: " . implode(', ', self::OPERATORS));
        }
    }

    private function reset(): void
    {
        $this->statement = '';
        $this->bindParams = [];
    }

    private function generatePlaceholder(string $field): string
    {
        return str_replace('.', '_', $field) . uniqid('_', true);
    }

    private function addCondition(string $type, string $field, string $operator, $value): SqlFnInterface
    {
        $this->validateOperator($operator);
        $placeholder = $this->generatePlaceholder($field);
        $this->statement .= " $type $field $operator :$placeholder";
        $this->bindParams[$placeholder] = $value;
        return $this;
    }

    public function select(string $table, array $fields = ['*']): SqlFnInterface
    {
        $this->statement = "SELECT " . implode(', ', $fields) . " FROM $table";
        return $this;
    }

    public function where(string $field, string $operator, $value): SqlFnInterface
    {
        return $this->addCondition('WHERE', $field, $operator, $value);
    }

    public function andWhere(string $field, string $operator, $value): SqlFnInterface
    {
        return $this->addCondition('AND', $field, $operator, $value);
    }

    public function orWhere(string $field, string $operator, $value): SqlFnInterface
    {
        return $this->addCondition('OR', $field, $operator, $value);
    }

    public function whereIn(string $field, array $values): SqlFnInterface
    {
        $placeholders = array_map(fn($index) => ":{$field}_{$index}", array_keys($values));
        $this->statement .= " WHERE $field IN (" . implode(', ', $placeholders) . ")";
        foreach ($values as $index => $value) {
            $this->bindParams["{$field}_{$index}"] = $value;
        }
        return $this;
    }

    public function andWhereIn(string $field, array $values): SqlFnInterface
    {
        $placeholders = array_map(fn($index) => ":{$field}_{$index}", array_keys($values));
        $this->statement .= " AND $field IN (" . implode(', ', $placeholders) . ")";
        foreach ($values as $index => $value) {
            $this->bindParams["{$field}_{$index}"] = $value;
        }
        return $this;
    }

    public function orWhereIn(string $field, array $values): SqlFnInterface
    {
        $placeholders = array_map(fn($index) => ":{$field}_{$index}", array_keys($values));
        $this->statement .= " OR $field IN (" . implode(', ', $placeholders) . ")";
        foreach ($values as $index => $value) {
            $this->bindParams["{$field}_{$index}"] = $value;
        }
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): SqlFnInterface
    {
        $this->statement .= " ORDER BY $field $direction";
        return $this;
    }

    public function limit(int $limit): SqlFnInterface
    {
        $this->statement .= " LIMIT $limit";
        return $this;
    }

    public function offset(int $offset): SqlFnInterface
    {
        $this->statement .= " OFFSET $offset";
        return $this;
    }

    public function groupBy(string $field): SqlFnInterface
    {
        $this->statement .= " GROUP BY $field";
        return $this;
    }

    public function having(string $field, string $operator, $value): SqlFnInterface
    {
        $this->validateOperator($operator);
        $placeholder = $this->generatePlaceholder($field);
        $this->statement .= " HAVING $field $operator :$placeholder";
        $this->bindParams[$placeholder] = $value;
        return $this;
    }

    public function execute(): array
    {
        try {
            $this->logQuery($this->statement, $this->bindParams);
            $stmt = $this->connection->prepare($this->statement);
            $stmt->execute($this->bindParams);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\QueryExecutionException $e) {
            $this->logError($e);
        } finally {
            $this->reset();
        }
        return [];
    }

    /**
     * @throws QueryExecutionException
     */
    public function insert(string $table, array $data): int
    {
        try {
            $fields = array_keys($data);
            $placeholders = array_map(fn($field) => ":$field", $fields);
            $this->statement = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $this->bindParams = $data;

            $this->logQuery($this->statement, $this->bindParams);
            $stmt = $this->connection->prepare($this->statement);
            $stmt->execute($this->bindParams);
            return $this->connection->lastInsertId();
        } catch (\PDOException $e) {
            $this->logError($e);
            throw new QueryExecutionException('Error executing insert query', 0, $e);
        } finally {
            $this->reset();
        }
    }

    public function update(string $table, array $data, int $id): int
    {
        try {
            $fields = array_keys($data);
            $setClause = implode(', ', array_map(fn($field) => "$field = :$field", $fields));
            $this->statement = "UPDATE $table SET $setClause WHERE id = :id";
            $data['id'] = $id;
            $this->bindParams = $data;

            $this->logQuery($this->statement, $this->bindParams);
            $stmt = $this->connection->prepare($this->statement);
            $stmt->execute($this->bindParams);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            $this->logError($e);
            throw new QueryExecutionException('Error executing update query', 0, $e);
        } finally {
            $this->reset();
        }
    }

    public function delete(string $table, int $id): int
    {
        try {
            $this->statement = "DELETE FROM $table WHERE id = :id";
            $this->bindParams = ['id' => $id];

            $this->logQuery($this->statement, $this->bindParams);
            $stmt = $this->connection->prepare($this->statement);
            $stmt->execute($this->bindParams);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            $this->logError($e);
            throw new QueryExecutionException('Error executing delete query', 0, $e);
        } finally {
            $this->reset();
        }
    }

    public function count(string $field = '*'): int
    {
        $this->statement = "SELECT COUNT($field) AS count FROM ($this->statement) AS subquery";
        $result = $this->execute();
        return $result[0]['count'] ?? 0;
    }

    public function sum(string $field): float
    {
        $this->statement = "SELECT SUM($field) AS sum FROM ($this->statement) AS subquery";
        $result = $this->execute();
        return $result[0]['sum'] ?? 0;
    }

    public function avg(string $field): float
    {
        $this->statement = "SELECT AVG($field) AS avg FROM ($this->statement) AS subquery";
        $result = $this->execute();
        return $result[0]['avg'] ?? 0;
    }

    public function min(string $field): float
    {
        $this->statement = "SELECT MIN($field) AS min FROM ($this->statement) AS subquery";
        $result = $this->execute();
        return $result[0]['min'] ?? 0;
    }

    public function max(string $field): float
    {
        $this->statement = "SELECT MAX($field) AS max FROM ($this->statement) AS subquery";
        $result = $this->execute();
        return $result[0]['max'] ?? 0;
    }

    public function distinct(string $field): SqlFnInterface
    {
        $this->statement .= " DISTINCT $field";
        return $this;
    }

    public function raw(string $query): SqlFnInterface
    {
        $this->statement = $query;
        return $this;
    }

    public function executeTransaction(callable $callback): void
    {
        try {
            $this->connection->beginTransaction();
            $callback($this);
            $this->connection->commit();
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            throw new QueryExecutionException('Transaction failed', 0, $e);
        }
    }

    private function logError(\Exception $e): void
    {
        // Assuming a Logger instance is available
        Logger::error($e->getMessage(), ['exception' => $e]);
    }

    private function logQuery(string $query, array $params): void
    {
        // Assuming a Logger instance is available
        Logger::info("Executing query: $query", ['params' => $params]);
    }


    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollBack(): void
    {
        $this->connection->rollBack();
    }
}
