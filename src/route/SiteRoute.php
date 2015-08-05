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

        $routemap = new \application\config\RouteMap();
        $estimated_route = $this->controller . '/' . $this->action;
        $real_route = $routemap->getRoute($estimated_route);
        list($this->controller, $this->action) = \explode('/', $real_route);

        if ($estimated_route === $real_route) {
            $real_params = \explode($estimated_route, $rt);
            if (\count($real_params) > 1) {
                \array_shift($real_params);
            }
            $params = \explode('/', \implode($estimated_route, $real_params));
            \array_shift($params);
        } else {
            if (strpos($estimated_route, $this->controller.'/') === 0) {
                $rt = substr($rt, strlen($this->controller)+1, strlen($rt));
                $params = \explode('/', $rt);
                                
            } else {
                $params = \explode('/', $rt);
            }
        }

        $this->params = new \stdClass();
        while (count($params)) {
            $key = \filter_var(\trim(\reset($params)), \FILTER_SANITIZE_ENCODED);
            \array_shift($params);
            $value = \filter_var(\trim(\reset($params)), \FILTER_SANITIZE_ENCODED);
            \array_shift($params);
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
