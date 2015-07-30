<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

use vsf\validators\ValidatorInterface as ValidatorInterface;
use vsf\observer\ObserverInterface as ObserverInterface;

/**
 * Basic controller class
 * @param stdClass $params
 * @param ResponseFactory $response
 */
abstract class Controller {

    protected $response;
    protected $params;
    protected $observers;
    protected $validators;

    public function __construct(\stdClass $params = null, response\ResponseInterface $response = null) {
        $this->params = $params;
        $this->response = $response;
        $this->observers = [];
        $this->validators = [];
    }

    public function addObserver(ObserverInterface $observer) {
        $this->observers[] = $observer;
    }

    public function addValidator(ValidatorInterface $validator) {
        $this->validators[] = $validator;
    }

    public function runValidators() {
        try {
            foreach ($this->validators as $validator) {
                if (!$validator->validate($this->params)) {
                    throw new validators\ValidatorException($validator->getMessage(), 400);
                }
            }
            return true;
        } catch (validators\ValidatorException $e) {
            echo $this->response->error($e->getMessage(),$this->params);
        }
        return false;
    }

}

/**
 * Controller Exceptions
 */
abstract class ControllerException extends \Exception {

    public function __construct($message = null, $code = 500, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        die($message);
    }

}

/*
 *  @throws ControllerNotFoundException
 */

class ControllerNotFoundException extends ControllerException {

    public function __construct($controller_name = null, $code = 500, Exception $previous = null) {
        $message = 'Controller not found: ' . $controller_name;
        parent::__construct($message, $code, $previous);
    }

}
