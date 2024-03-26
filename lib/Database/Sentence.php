<?php

//Model: beschreibt Itemtabele für Controller

namespace Nachtmerrie\Database;

class Sentence extends Table
{

    public function getPrimaryKey(): array
    {
        return ['id'];
    }

    public function getForeignKey(): array
    {
        return ['item_id'];
    }

    public function getColumns(): array
    {
        return [
            'id',
            'item_id',
            'nl',
            'de'
        ];
    }

    public function getTableName(): string
    {
        return 'sentence';
    }
}