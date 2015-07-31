<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\route;

use \application\config\Application as config;

class SiteRoute extends Route implements RouteInterface {

    public function __construct() {
        $rt = \filter_input(\INPUT_GET, 'rt');
        $route = \explode('/', $rt);
        $this->controller = \trim(\reset($route));
        \array_shift($route);
        $this->action = \trim(\reset($route));
        \array_shift($route);
        $this->params = new \stdClass();
        while (count($route)) {
            $key = \filter_var(\trim(\reset($route)), \FILTER_SANITIZE_ENCODED);
            \array_shift($route);
            $value = \filter_var(\trim(\reset($route)), \FILTER_SANITIZE_ENCODED);
            \array_shift($route);
            if (!empty($key) && !empty($value)) {
                $this->params->{$key} = $value;
            }
        }
    }

    public function getController() {
        return empty($this->controller) ? config::DEFAULT_SITE_CONTROLLER : $this->controller;
    }

    public function getAction() {
        return empty($this->action) ? config::DEFAULT_SITE_ACTION : $this->action;
    }

    public function getParams() {
        return $this->params;
    }

}
