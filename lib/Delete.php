<?php
//DELETE FROM table_name WHERE condition;

namespace Nachtmerrie;

use PDO;
use PDOStatement;
use Nachtmerrie\Database\Table;

class Delete
{
    /**
     * @var PDO
     */
    protected $connection;
    /**
     * @var array
     */
    protected $value;
    /**
     * @var Table
     */
    protected $deleteFrom;
    /**
     * @var array
     */
    protected $column;
    /**
     * @var string
     */
    protected $condition;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array $insertValues
     * @return $this
     */
    public function value(array $insertValues): self
    {
        $this->value = $insertValues;
        return $this;
    }

    /**
     * @param array $column
     * @return $this
     */
    public function column(array $column): self
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @param Table $table
     * @return $this
     */
    public function deleteFrom(Table $table): self
    {
        $this->deleteFrom = $table;
        return $this;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function where( string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->prepareDelete()->execute();
    }

    /**
     * @return PDOStatement
     */
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