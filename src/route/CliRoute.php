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
        $p = 0;
        if (!is_null($argv)) {
            foreach ($argv as $param) {
                $this->params->{'p' . $p++} = \filter_var($param, \FILTER_SANITIZE_ENCODED);
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
