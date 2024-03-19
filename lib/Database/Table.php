<?php
namespace Nachtmerrie\Database;

abstract class Table

{

    /**

     * Get the primary key of the table

     *

     * @return array

     */

    abstract public function getPrimaryKey(): array;

    /**

     * Get the columns of the table

     *

     * @return array

     */

    abstract public function getColumns(): array;

    /**

     * Get the name of the table

     *

     * @return string

     */

    abstract public function getTableName(): string;

}