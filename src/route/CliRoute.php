<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\route;

use application\config\Application as config;

class CliRoute extends Route implements RouteInterface {

    public function __construct($argv) {
        \array_shift($argv);
        $controller = \trim(reset($argv));
        $this->controller = \filter_var($controller, \FILTER_SANITIZE_STRING);
        \array_shift($argv);
        $action = \trim(\reset($argv));
        $this->action = \filter_var($action, \FILTER_SANITIZE_STRING);
        \array_shift($argv);
        $this->params = new \stdClass();
        while (count($argv)) {
            $key = \filter_var(\trim(\reset($argv)), \FILTER_SANITIZE_ENCODED);
            \array_shift($argv);
            $value = \filter_var(\trim(\reset($argv)), \FILTER_SANITIZE_ENCODED);
            \array_shift($argv);
            if (!empty($key) && !empty($value)) {
                $this->params->{$key} = $value;
            }
        }
    }

    public function getController() {
        return empty($this->controller) ? config::DEFAULT_CLI_CONTROLLER : $this->controller;
    }

    public function getAction() {
        return empty($this->action) ? config::DEFAULT_CLI_ACTION : $this->action;
    }

    public function getParams() {
        return $this->params;
    }

}
