<?php

namespace Nachtmerrie;

use InvalidArgumentException;
use Nachtmerrie\Database\Table;
use PDO;
use PDOStatement;

class Edit
{
    /** @var PDO */
    private $connection;

    /** @var Table */
    private $updateTable;

    /** @var array */
    private $values;

    /** @var string */
    private $where;

    /** @var array */
    private $whereData;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function table(Table $table): self
    {
        $this->updateTable = $table;

        return $this;
    }

    public function values(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function where(string $whereStmt, array $whereData): self
    {
        $this->where = $whereStmt;
        $this->whereData = $whereData;

        return $this;
    }

    public function execute(): bool
    {
        return $this->prepare()->execute();
    }

    private function prepare(): PDOStatement
    {
        $availableColumns = $this->updateTable->getColumns();
        $tableName = $this->updateTable->getTableName();
        $updateString = '';
        $first = true;

        foreach (array_keys($this->values) as $column) {
            if (!in_array($column, $availableColumns)) {
                throw new InvalidArgumentException(
                    "Column '$column' is not in table's column list (Table: '$tableName')"
                );
            }

            if (!$first) {
                $updateString .= ', ';
            }

            $updateString .= "$column = :new$column";

            $first = false;
        }

        $query = "UPDATE %s SET %s WHERE %s";

        $stmt = $this->connection->prepare(
            sprintf(
                $query,
                $tableName,
                $updateString,
                $this->where)
        );

        foreach ($this->values as $column => $value) {
            $stmt->bindValue(":new$column", $value);
        }

        foreach ($this->whereData as $column => $value) {
            $stmt->bindValue("$column", $value);
        }

        return $stmt;
    }
}