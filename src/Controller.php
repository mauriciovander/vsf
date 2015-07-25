<?php

namespace vsf;

abstract class Controller {

    protected $response;
    protected $params;

    public function __construct(\stdClass $params = null, ResponseFactory $response = null) {
        $this->params = $params;
        $this->response = $response;
    }

}

abstract class ControllerException extends \Exception {

    public function __construct($message = null, $code = 500, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        die($message);
    }

}

class ControllerNotFoundException extends ControllerException {

    public function __construct($controller_name = null, $code = 500, Exception $previous = null) {
        $message = 'Controller not found: ' . $controller_name;
        parent::__construct($message, $code, $previous);
    }

}
