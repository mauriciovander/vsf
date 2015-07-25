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
        $response_strategy = new ResponseStrategy($this->context);
        $this->response = $response_strategy->getResponse();
        $this->response->setHeaders();
    }

    private function setRouteFromContext($argv) {
        $route_strategy = new RouteStrategy($this->context, $argv);
        $this->route = $route_strategy->getRoute();
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
