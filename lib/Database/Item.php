<?php

//Model: beschreibt Itemtabele für Controller

namespace Nachtmerrie\Database;

class Item extends Table
{

    public function getPrimaryKey(): array
    {
        return ['id'];
    }

    public function getColumns(): array
    {
        return [
            'id',
            'nl',
            'de'
        ];
    }

    public function getTableName(): string
    {
        return 'item';
    }
}