<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\route;

class RouteFactory {

    public static function create($context, $argv = null) {
        $factory = '\\vsf\\route\\' . \ucwords($context) . 'Route';
        return new $factory($argv);
    }

}

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
