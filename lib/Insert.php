<?php
/** INSERT INTO table_name (column1, column2, column3, ...)
VALUES (value1, value2, value3, ...); **/

namespace Nachtmerrie;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Nachtmerrie\Database\Table;

class Insert
{
    /**
     * @var PDO The connection to use
     */
    protected $connection;
    /**

    /** @var array The value to insert */
    protected $value;
    /**
     * @var Table the table to insert into
     */
    protected $insertInto;


    //Connection to Database
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertInto(Table $table): self
    {
        $this->insertInto = $table;
        return $this;
    }
    public function value(array $insertValues): self
    {
        $this->value = $insertValues;
        return $this;
    }
    public function execute()
    {
        $this->prepareInsert()->execute();
    }

    protected function prepareInsert(): PDOStatement
    {
        $availableColumns = $this->insertInto->getColumns();
        $tableName = $this->insertInto->getTableName();
        $placeholders = [];

        foreach (array_keys($this->value) as $column) {
            if (!in_array($column, $availableColumns)) {
                throw new InvalidArgumentException(
                    "Column $column is not in table's column list (Table: $tableName)"
                );
            }
            $placeholders[] = ":$column";
        }

        $columnString = implode(', ', array_keys($this->value));
        $placeholderString = implode(', ', $placeholders);


        $query = "INSERT INTO %s (%s) VALUES (%s)";

        $stmt = $this->connection->prepare(
            sprintf(
                $query,
                $tableName,
                $columnString,
                $placeholderString
            )
        );
        foreach ($this->value as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }

        return $stmt;
    }
}