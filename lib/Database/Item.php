<?php

//Model: beschreibt Itemtabele für Controller

namespace Nachtmerrie\Database;

class Item extends Table
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
    public function getColumns(): array
    {
        return [
            'id',
            'nl',
            'de'
        ];
    }
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'item';
    }
}