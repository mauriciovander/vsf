<?php

namespace vsf;

use application\config\DB as config;

abstract class Model implements ModelInterface {

    protected $observers;
    protected $data;
    protected $class_name;
    protected $table_name;
    protected $primary_key_name;
    protected $db_name;
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
        $this->observers = [];
        $this->data = [];
        $this->class_name = get_class($this);
        $this->db_name = config::DATABASE;
        $this->table_name = strtolower(substr(end(explode('/', get_class($this))), -4));
        $this->primary_key_name = 'id_' . $this->table_name;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function addObserver(ModelObserverInterface $observer) {
        $this->observers[] = $observer;
    }

    private function notifyObservers($subject) {
        foreach ($this->observers as $observer) {
            $observer->update($this->class_name, $subject, $this->data);
        }
    }

    public function update() {
        try {

            $prepared = [];
            $prepared_sql = [];
            foreach ($this->data as $key => $value) {
                $prepared_sql[] = $key . '=:' . $key;
                $prepared[':' . $key] = $value;
            }

            $sql = 'update ' . $this->db_name . '.' . $this->table_name
                    . ' set ' . implode(',', $prepared_sql)
                    . ' where ' . $this->primary_key_name . '=:id';

            $prepared_statement = $this->db->prepare($sql);

            foreach ($this->data as $key => $value) {
                $prepared_statement->bindParam($prepared);
            }
            $prepared_statement->bindParam(':id', $this->{$this->primary_key_name});

            $prepared_statement->execute();

            $this->notifyObservers('update');
        } catch (Exception $e) {
            throw new ModelUpdateException($this->class_name, 500, $e);
        }
    }

    public function delete() {
        try {
            $sql = 'delete from ' . $this->db_name . '.' . $this->table_name
                    . ' where ' . $this->primary_key_name . '=:id';

            $prepared_statement = $this->db->prepare($sql);
            $prepared_statement->bindParam(':id', $this->{$this->primary_key_name});
            $prepared_statement->execute();

            $this->notifyObservers('delete');
        } catch (Exception $e) {
            throw new ModelDeleteException($this->class_name, 500, $e);
        }
    }

    public function create() {
        try {
            $prepared = [];
            foreach ($this->data as $key => $value) {
                $prepared[':' . $key] = $value;
            }

            $sql = 'insert into ' . $this->db_name . '.' . $this->table_name
                    . ' (' . $this->primary_key_name;
            if (count($this->data)) {
                $sql .= ',' . implode(',', array_keys($this->data));
            }
            $sql .= ') values (null';
            if (count($this->data)) {
                $sql .= ',' . implode(',', array_keys($prepared));
            }
            $sql .= ')';

            $prepared_statement = $this->db->prepare($sql);

            if ($prepared_statement->execute()) {
                $this->load($this->db->lastInsertId());
            }
            
            $this->notifyObservers('create');
        } catch (Exception $e) {
            throw new ModelInsertException($this->class_name, 500, $e);
        }
    }

    public function load($id) {
        try {
            $sql = 'select *'
                    . ' from ' . $this->db_name . '.' . $this->table_name
                    . ' where ' . $this->primary_key_name . '=:id limit 1';

            $prepared_statement = $this->db->prepare($sql);
            $prepared_statement->execute(array(':id' => $id));
            $results = $prepared_statement->fetchAll();

            if (!$results) {
                return false;
            }

            $this->data = reset($results);

            $this->notifyObservers('load');
        } catch (Exception $e) {
            throw new ModelInsertException($this->class_name, 500, $e);
        }
    }

    public function loadLast() {
        try {
            $sql = 'select *'
                    . ' from ' . $this->db_name . '.' . $this->table_name
                    . ' order by ' . $this->primary_key_name . ' desc limit 1';

            $prepared_statement = $this->db->prepare($sql);
            $prepared_statement->execute();
            $results = $prepared_statement->fetchAll();

            if (!$results) {
                return false;
            }

            $this->data = reset($results);

            $this->notifyObservers('load');
        } catch (Exception $e) {
            throw new ModelInsertException($this->class_name, 500, $e);
        }
    }

    public function loadFirst() {
        try {

            $sql = 'select *'
                    . ' from ' . $this->db_name . '.' . $this->table_name
                    . ' order by ' . $this->primary_key_name . ' asc limit 1';

            $prepared_statement = $this->db->prepare($sql);
            $prepared_statement->execute();
            $results = $prepared_statement->fetchAll();

            if (!$results) {
                return false;
            }

            $this->data = reset($results);

            $this->notifyObservers('load');
        } catch (Exception $e) {
            throw new ModelInsertException($this->class_name, 500, $e);
        }
    }

    public function getData() {
        return $this->data;
    }

}

/**
 *  Model Interface
 */
interface ModelInterface {
    
}

/**
 *  Model Observers
 */
interface ModelObserverInterface {

    public function update($model, $subject, $data);
}

class ModelObserver implements ModelObserverInterface {

    public function update($model, $subject, $data) {
        echo 'MODEL: ' . $model . PHP_EOL;
        echo 'SUBJECT: ' . $subject . PHP_EOL;
        var_dump($data);
    }

}

/**
 *  Model Exceptions
 */
abstract class ModelException extends \Exception {

    public function __construct($message = '', $code = null, $previous = null) {
        parent::__construct('MODEL | ' . $message, $code, $previous);
    }

}

class ModelUpdateException extends ModelException {

    public function __construct($message = '', $code = null, $previous = null) {
        parent::__construct('UPDATE | ' . $message, $code, $previous);
    }

}

class ModelInsertException extends ModelException {

    public function __construct($message = '', $code = null, $previous = null) {
        parent::__construct('INSERT | ' . $message, $code, $previous);
    }

}

class ModelDeleteException extends ModelException {

    public function __construct($message = '', $code = null, $previous = null) {
        parent::__construct('DELETE | ' . $message, $code, $previous);
    }

}

class ModelLoadException extends ModelException {

    public function __construct($message = '', $code = null, $previous = null) {
        parent::__construct('LOAD | ' . $message, $code, $previous);
    }

}
