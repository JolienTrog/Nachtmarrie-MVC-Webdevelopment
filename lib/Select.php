<?php
namespace Nachtmerrie;
use Nachtmerrie\Database\Table;
use PDO;
use PDOStatement;
class Select
{
    /** @var PDO The connection to use */
    protected $connection;
    /** @var array The columns to select */
    protected $columns;
    /** @var Table The table to select from */
    protected $from;
    /** @var string The where statement */
    protected $where;
    /** @var array The data for the where statement */
    protected $whereData;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }
    public function from(Table $table): self
    {
        $this->from = $table;
        return $this;
    }
    public function where(string $whereStmt, array $whereData): self
    {
        $this->where = $whereStmt;
        $this->whereData = $whereData;
        return $this;
    }
    public function fetchAll(): array
    {
        $stmt = $this->prepareExec();
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function prepareExec(): PDOStatement
    {
        $availableColumns = $this->from->getColumns();
        $colsToSelect = [];
        foreach ($this->columns as $column) {
            if ($column != '*' && !in_array($column, $availableColumns)) {
                echo $column . ' is not an available column in ' . $this->from->getTableName() . PHP_EOL;
                continue;
            }
            $colsToSelect[] = $column;
        }
        $query = "SELECT %s FROM %s";
        if ($this->where) {
            $query .= ' WHERE ' . $this->where;
        }
        $stmt = $this->connection->prepare(
            sprintf(
                $query,
                implode(', ', $colsToSelect),
                $this->from->getTableName()
            )
        );
        foreach ($this->whereData as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt;
    }
}