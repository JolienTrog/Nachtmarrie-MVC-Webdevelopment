<?php
//DELETE FROM table_name WHERE condition;

namespace Nachtmerrie;

use PDO;
use PDOStatement;
use Nachtmerrie\Database\Table;

class Delete
{
    protected $connection;
    protected $value;
    protected $deleteFrom;
    protected $column;

    protected $condition;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function value(array $insertValues): self
    {
        $this->value = $insertValues;
        return $this;
    }
    public function column(array $column): self
    {
        $this->column = $column;
        return $this;
    }
    public function deleteFrom(Table $table): self
    {
        $this->deleteFrom = $table;
        return $this;
    }
    public function where( string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function execute()
    {
        $this->prepareDelete()->execute();
    }

    protected function prepareDelete(): PDOStatement
    {
        //SQL DELETE FROM items WHERE id = ?;
        $query = "DELETE FROM %s WHERE %s";
        $tableName = $this->deleteFrom->getTableName();

        $stmt = $this->connection->prepare(
            sprintf(
                $query,
                $tableName,
                $this->condition
            )
        );

        foreach ($this->value as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt;
    }

}