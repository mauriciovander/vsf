<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\route;

use application\config\Application as config;

class AjaxRoute extends Route implements RouteInterface {

    public function __construct() {
        $rt = \filter_input(\INPUT_GET, 'rt');
        $route = \explode('/', $rt);
        $controller = \trim(\reset($route));
        $this->controller = \filter_var($controller, \FILTER_SANITIZE_STRING);
        \array_shift($route);
        $action = \trim(\reset($route));
        $this->action = \filter_var($action, \FILTER_SANITIZE_STRING);
        $this->params = new \stdClass();
        foreach (\array_keys($_POST) as $key) {
            $this->params->{$key} = \filter_input(\INPUT_POST, $key, \FILTER_SANITIZE_ENCODED);
        }
    }

    public function getController() {
        return empty($this->controller) ? config::DEFAULT_AJAX_CONTROLLER : $this->controller;
    }

    public function getAction() {
        return empty($this->action) ? config::DEFAULT_AJAX_ACTION : $this->action;
    }

    public function getParams() {
        return $this->params;
    }

}
