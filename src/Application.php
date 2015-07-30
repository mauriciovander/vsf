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
    private $action_config;

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
        $this->loadActionConfig();
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

    private function loadActionConfig() {
        $endpoint_name = '\\application\\endpoints\\'
                . $this->route->getController()
                . '\\'
                . $this->route->getAction();

        if (class_exists($endpoint_name)) {
            $endpoint = new $endpoint_name ();
            foreach ($endpoint->getValidators() as $validator) {
                $this->controller->addValidator(new $validator());
            }
        } else {
            $log = new \Monolog\Logger('Config');
            $log->addWarning('No configuration found for endpoint', array($endpoint_name,$this->context));
        }
    }

    private function loadControllerFile() {
        $controller_name = ucwords($this->route->getController());
        $controller_class = 'application\\controllers\\' . $controller_name . 'Controller';
        try {
            $this->controller = new $controller_class($this->params, $this->response);
        } catch (Exception $e) {
            throw new ControllerNotFoundException($controller_name, 400, $e);
        }
    }

    public function run() {
        if ($this->controller->runValidators()) {
            $method_name = $this->route->getAction();
            $this->controller->$method_name();
        }
    }

}
