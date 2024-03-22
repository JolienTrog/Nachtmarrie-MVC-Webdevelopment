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

        $tableName = $this->deleteFrom->getTableName();



        $query = "DELETE FROM %s WHERE %s";

        $stmt = $this->connection->prepare(
            sprintf(
                $query,
                $tableName,
                $this->condition
            )
        );
        // DELETE FROM %s WHERE id = :id AND username = :username


        // [':id' => 123345, ':username' => 'testUser' ]
        foreach ($this->value as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt;
    }

}