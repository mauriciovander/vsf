<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

use application\config\DB as config;

/**
 * Singleton Database connection class
 * uses PDO
 * @link http://php.net/manual/es/book.pdo.php
 */
class DB {

    public static $instance = null;
    private $db;

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
            $this->db = new \PDO($connection_string, $user, $password);
        } catch (PDOException $e) {
            throw new Controller_Exception('Connection Error', 1, $e);
        }
    }

    public function prepare($sql) {
        return $this->db->prepare($sql);
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    private function __clone() {
        // avoid cloning this instance
    }

}
