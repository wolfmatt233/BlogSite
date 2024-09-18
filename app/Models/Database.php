<?php

namespace App\Models;

use App\Controller;

class Database
{
    private $config = array(
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'testing',
    );

    private $connection = NULL;

    static private $instance = NULL;

    private function __construct()
    {
        $this->connection = @new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['password'],
            $this->config['database'],
        );

        if (mysqli_connect_errno() != 0) {
            $view = new Controller();
            $view->render('error', ['message' => "Could not connect to the database."]);
        }
    }

    static public function getDatabase()
    {
        if (self::$instance == NULL) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}