<?php

namespace Nachtmerrie\Controller;

use PDO;

abstract class Controller
{
    /**
     * @var PDO
     */
    protected $connection;

    /**
     * set the connection via Constructor
     */
    public function __construct()
    {
        $this->setConnection();
    }

    /**
     * Connection details with admit-user
     *
     * @return void
     */
    protected function setConnection()
    {
        $this->connection = new PDO(
            'mysql:host=localhost;dbname=nachtmerrie',
            'admin',
            'admin123'
        );
    }
}