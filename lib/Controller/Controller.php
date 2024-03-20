<?php

namespace Nachtmerrie\Controller;

use PDO;

abstract class Controller
{
    /**
     * @var PDO
     */
    protected $connection;

    public function __construct()
    {
        $this->setConnection();
    }

    protected function setConnection()
    {
        $this->connection = new PDO(
            'mysql:host=localhost;dbname=nachtmerrie',
            'admin',
            'admin123'
        );
    }
//    public function indexAction(): void;

}