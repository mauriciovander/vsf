<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

use application\config\DB as config;

/**
 * Basic Model for simple CRUD applications
 * Observers can suscribe to a model using addObserver to 
 * monitor or log model interactions
 */
abstract class Model implements ModelInterface {

    protected $data;
    protected $class_name;
    protected $table_name;
    protected $primary_key_name;
    protected $db_name;
    protected $observers;
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

    /*
     * set a field value in $this->data
     * @example $model->field_name = 'field_value';
     */

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /*
     * get a field value from $this->data
     * @example $field_value = $model->field_name;
     */

    public function __get($name) {
        return $this->data[$name];
    }

    /**
     * Suscribes an Observer to the model
     * @example $model->addObserver(new ModelObserver());
     * @param \vsf\ModelObserverInterface $observer
     */
    public function addObserver(ModelObserverInterface $observer) {
        $this->observers[] = $observer;
    }

    /**
     * Notify every registered observers
     * $this->data is sent to every observer
     * @param string $subject
     */
    private function notifyObservers($subject) {
        foreach ($this->observers as $observer) {
            $observer->update($this->class_name, $subject, $this->data);
        }
    }

    /**
     * saves model changes in the database table using PDO connection
     * @throws ModelUpdateException
     */
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

    /**
     * deletes model from the database table using PDO connection
     * @throws ModelDeleteException
     */
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

    /**
     * Insert a new row into the database table using PDO connection
     * @throws ModelInsertException
     */
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

    /**
     * Retrieves model information from the database table using PDO connection
     * @param type $id
     * @return boolean
     * @throws ModelLoadException
     */
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
            throw new ModelLoadException($this->class_name, 500, $e);
        }
    }

    /**
     * Retrieves the information of last model inserted into 
     * the database table using PDO connection
     * @return boolean
     * @throws ModelLoadException
     */
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
            throw new ModelLoadException($this->class_name, 500, $e);
        }
    }

    /**
     * Retrieves the information of first model inserted into 
     * the database table using PDO connection
     * @return boolean
     * @throws ModelLoadException
     */
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
            throw new ModelLoadException($this->class_name, 500, $e);
        }
    }

    /**
     * Get model information
     * @return array $this->data
     */
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
 *  Model Observer Interface
 */
interface ModelObserverInterface {

    /**
     * @param string $model
     * @param string $subject
     * @param mixed $data
     */
    public function update($model, $subject, $data);
}

class ModelObserver implements ModelObserverInterface {

    public function update($model, $subject, $data) {
        $log = new \Monolog\Logger($model);
        $log->addNotice($subject,$data);
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
