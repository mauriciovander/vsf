<?php

namespace vsf;
use application\config\Application as config;

interface RouteInterface {

    public function getController();

    public function getAction();

    public function getParams();
}

abstract class Route {
    private $controller;
    private $action;
    private $params;

}

class CliRoute extends Route implements RouteInterface {

    public function __construct($argv) {
        \array_shift($argv);
        $controller = \trim(reset($argv));
        $this->controller = \filter_var($controller, \FILTER_SANITIZE_STRING);
        \array_shift($argv);
        $action = \trim(\reset($argv));
        $this->action = \filter_var($action, \FILTER_SANITIZE_STRING);
        \array_shift($argv);
        $this->params = array();
        if (!is_null($argv)) {
            foreach ($argv as $param) {
                $this->params[] = \filter_var($param, \FILTER_SANITIZE_ENCODED);
            }
        }
    }

    public function getController() {
        return empty($this->controller)?config::DEFAULT_CLI_CONTROLLER:$this->controller;
    }

    public function getAction() {
        return empty($this->action)?config::DEFAULT_CLI_ACTION:$this->action;
    }

    public function getParams() {
        return $this->params;
    }

}

class AjaxRoute extends Route implements RouteInterface {

    public function __construct() {
        $rt = \filter_input(\INPUT_GET, 'rt');
        $route = \explode('/', $rt);
        $controller = \trim(\reset($route));
        $this->controller = \filter_var($controller, \FILTER_SANITIZE_STRING);
        \array_shift($route);
        $action = \trim(\reset($route));
        $this->action = \filter_var($action, \FILTER_SANITIZE_STRING);
        $this->params = array();
        foreach (\array_keys($_POST) as $key) {
            $this->params[$key] = \filter_input(\INPUT_POST, $key, \FILTER_SANITIZE_ENCODED);
        }
    }

    public function getController() {
        return empty($this->controller)?config::DEFAULT_AJAX_CONTROLLER:$this->controller;
    }

    public function getAction() {
        return empty($this->action)?config::DEFAULT_AJAX_ACTION:$this->action;
    }

    public function getParams() {
        return $this->params;
    }

}

class SiteRoute extends Route implements RouteInterface {

    public function __construct() {
        $rt = \filter_input(\INPUT_GET, 'rt');
        $route = \explode('/', $rt);
        $controller = \trim(\reset($route));
        $this->controller = \filter_var($controller, \FILTER_SANITIZE_STRING);
        \array_shift($route);
        $action = \trim(\reset($route));
        $this->action = \filter_var($action, \FILTER_SANITIZE_STRING);
        \array_shift($route);
        $this->params = array();
        if (!is_null($route)) {
            foreach ($route as $param) {
                $this->params[] = \filter_var($param, \FILTER_SANITIZE_ENCODED);
            }
        }
    }

    public function getController() {
        return empty($this->controller)?config::DEFAULT_SITE_CONTROLLER:$this->controller;
    }

    public function getAction() {
        return empty($this->action)?config::DEFAULT_SITE_ACTION:$this->action;
    }

    public function getParams() {
        return $this->params;
    }

}

class ApiRoute extends Route implements RouteInterface {

    public function __construct() {
        $rt = \filter_input(\INPUT_GET, 'rt');
        $route = \explode('/', $rt);
        $controller = \trim(\reset($route));
        $this->controller = \filter_var($controller, \FILTER_SANITIZE_STRING);
        \array_shift($route);
        $action = \trim(\reset($route));
        $this->action = \filter_var($action, \FILTER_SANITIZE_STRING);
        $this->params = array();
        foreach (\array_keys($_POST) as $key) {
            $this->params[$key] = \filter_input(\INPUT_POST, $key, \FILTER_SANITIZE_ENCODED);
        }
    }

    public function getController() {
        return empty($this->controller)?config::DEFAULT_API_CONTROLLER:$this->controller;
    }

    public function getAction() {
        return empty($this->action)?config::DEFAULT_API_ACTION:$this->action;
    }

    public function getParams() {
        return $this->params;
    }

}

class RouteStrategy {

    private $route_strategy;
    private $context;

    public function __construct($context, $argv = null) {
        $this->context = $context;
        $this->selectRouteStrategyFromContext($argv);
    }

    private function selectRouteStrategyFromContext($argv) {
        switch ($this->context) {
            case Context::AJAX:
                $this->route_strategy = new AjaxRoute;
                break;
            case Context::API:
                $this->route_strategy = new ApiRoute;
                break;
            case Context::CLI:
                $this->route_strategy = new CliRoute($argv);
                break;
            case Context::SITE:
                $this->route_strategy = new SiteRoute;
                break;
        }
    }

    public function getRoute() {
        return $this->route_strategy;
    }

}
