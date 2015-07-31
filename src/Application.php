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
        try {
            $endpoint_name = '\\application\\endpoints\\'
                    . $this->route->getController()
                    . '\\'
                    . $this->route->getAction();

            if (class_exists($endpoint_name)) {
                $endpoint = new $endpoint_name ();
                $this->checkValidContext($endpoint);
                $this->runValidators($endpoint);
                $this->addObservers($endpoint);
            } else {
                $log = new \Monolog\Logger('Config');
                $log->addWarning('No configuration found for endpoint', array($endpoint_name, $this->context));
            }
        } catch (UnknownContextException $e) {
            die($this->response->error('Invalid Context: ' . $this->context));
        } catch (validators\ValidatorException $e) {
            die($this->response->error($e->getMessage(), $this->params));
        }
    }

    private function checkValidContext(EndpointInterface $endpoint) {
        if (!in_array($this->context, $endpoint->getValidContexts())) {
            throw new UnknownContextException($this->context, 400);
        }
    }

    private function runValidators(EndpointInterface $endpoint) {
        foreach ($endpoint->getValidators() as $validator) {
            if (!($validator instanceof validators\ValidatorInterface)) {
                throw new validators\ValidatorException('Invalid validator', 400);                
            }
            if (!$validator->validate($this->params)) {
                throw new validators\ValidatorException($validator->getMessage(), 400);
            }
        }
        return true;
    }

    private function addObservers(EndpointInterface $endpoint) {
        foreach ($endpoint->getObservers() as $observer) {
            $this->controller->addObserver($observer);
        }
    }

    function loadControllerFile() {
        $controller_name = ucwords($this->route->getController());
        $controller_class = 'application\\controllers\\' . $controller_name . 'Controller';
        try {
            $this->controller = new $controller_class($this->params, $this->response);
        } catch (Exception $e) {
            throw new ControllerNotFoundException($controller_name, 400, $e);
        }
    }

    function run() {
        $method_name = $this->route->getAction();
        $this->controller->$method_name();
    }

}
