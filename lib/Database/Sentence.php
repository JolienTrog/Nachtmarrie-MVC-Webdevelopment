<?php

//Model: beschreibt Itemtabele für Controller

namespace Nachtmerrie\Database;

class Sentence extends Table
{
    /**
     * @return array
     */
    public function getPrimaryKey(): array
    {
        return ['id'];
    }
    /**
     * @return array
     */
    public function getForeignKey(): array
    {
        return ['item_id'];
    }
    /**
     * @return array
     */
    public function getColumns(): array
    {
        return [
            'id',
            'item_id',
            'nl',
            'de'
        ];
    }
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'sentence';
    }
}