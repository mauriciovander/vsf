<?php

namespace Vsf;

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
        $this->setParamsFromContext($argv);
        $this->loadControllerFile();
    }

    private function selectResponseFromContext() {
        $response_strategy = new \Vsf\ResponseStrategy($this->context);
        $this->response = $response_strategy->getResponse();
        $this->response->setHeaders();
    }

    private function setParamsFromContext($argv) {
        $this->params = new \stdClass();
        switch ($this->context) {
            case \Vsf\Context::CLI:
                $n = 0;
                foreach ($argv as $param) {
                    $this->params->{'p' . $n++} = $param;
                }
                break;
            case \Vsf\Context::API:
                foreach (array_keys($_POST) as $param) {
                    $this->params->{$param} = filter_input(INPUT_POST, $key);
                }
                break;
            case \Vsf\Context::AJAX:
                foreach (array_keys($_POST) as $param) {
                    $this->params->{$param} = filter_input(INPUT_POST, $key);
                }
                break;
            case \Vsf\Context::CLI:
                $rt = filter_input(INPUT_GET, 'rt');
                $n = 0;
                foreach (explode('/', $rt) as $param) {
                    $this->params->{'p' . $n++} = $param;
                }
                break;
        }
    }

    private function setRouteFromContext($argv) {
        $route_strategy = new \Vsf\RouteStrategy($this->context, $argv);
        $this->route = $route_strategy->getRoute();
    }

    public function loadControllerFile() {
        $controller_name = ucwords($this->route->getController());
        $controller_class = '\\application\\controllers\\' . $controller_name . 'Controller';
        try {
            $this->controller = new $controller_class($this->params, $this->response);
        } catch (Exception $e) {
            throw new \Vsf\ControllerNotFoundException($controller_name, 400, $e);
        }
    }

    public function run() {
        $method_name = $this->route->getAction();
        $this->controller->$method_name();
    }

}
