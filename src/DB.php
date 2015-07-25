<?php

namespace vsf;

use application\config\DB as config;

class DB {

    public static $instance = null;
    protected $db;

    public function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        try {
            $connection_string = 'mysql:'
                    . 'host=' . config::HOST
                    . ';port=' . config::PORT
                    . ';dbname=' . config::DATABASE;
            $user = config::USER;
            $password = config::PASSWORD;
//            $this->db = new \PDO($connection_string, $user, $password);
        } catch (PDOException $e) {
            throw new Controller_Exception('Connection Error', 1, $e);
        }
    }

    private function __clone() {
        
    }

}
