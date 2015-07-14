<?php

namespace Vsf;

class Application {

    private $response;
    private $context;
    private $route;
    private $params;

// response_factory could be ApiResponseFactory or AjaxResponseFactory
    public function __construct($context, $argv = null) {
        $this->context = $context;
        $this->init($argv);
        $this->run();
    }

    private function init($argv) {
        $this->selectResponseFromContext();
        $this->setRouteFromContext($argv);
        $this->checkParams();
    }

    private function selectResponseFromContext() {
        $response_strategy = new \Vsf\ResponseStrategy($this->context);
        $this->response = $response_strategy->getResponse();
        $this->response->setHeaders();
    }

    private function setRouteFromContext($argv) {
        $route_strategy = new \Vsf\RouteStrategy($this->context, $argv);
        $this->route = $route_strategy->getRoute();
        echo $this->route->getController();
        echo $this->route->getAction();
        echo json_encode($this->route->getParams());
    }

    private function checkParams() {
        $this->params = null;
    }

    private function run() {
        
    }

}
