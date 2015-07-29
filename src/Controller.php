<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

/**
 * Basic controller class
 * @param stdClass $params
 * @param ResponseFactory $response
 */
abstract class Controller {

    protected $response;
    protected $params;

    public function __construct(\stdClass $params = null, response\ResponseInterface $response = null) {
        $this->params = $params;
        $this->response = $response;
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
