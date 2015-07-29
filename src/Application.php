<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

class Application {

    private $response;
    private $context;
    private $route;
    private $params;
    private $controller;

// response_factory could be ApiResponseFactory or AjaxResponseFactory
    public function __construct($context, $argv = null) {
        $this->context = $context;
        $this->init($argv);
        $this->run();
    }

    private function init($argv) {
        $this->selectResponseFromContext();
        $this->setRouteFromContext($argv);
        $this->setParams();
        $this->loadControllerFile();
    }

    private function selectResponseFromContext() {
        $this->response = response\ResponseFactory::create($this->context);
        $this->response->setHeaders();
    }

    private function setRouteFromContext($argv) {
        $this->route = route\RouteFactory::create($this->context, $argv);
    }

    private function setParams() {
        $this->params = $this->route->getParams();
    }

    public function loadControllerFile() {
        $controller_name = ucwords($this->route->getController());
        $controller_class = 'application\\controllers\\' . $controller_name . 'Controller';
        try {
            $this->controller = new $controller_class($this->params, $this->response);
        } catch (Exception $e) {
            throw new ControllerNotFoundException($controller_name, 400, $e);
        }
    }

    public function run() {
        $method_name = $this->route->getAction();
        $this->controller->$method_name();
    }

}
