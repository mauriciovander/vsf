<?php

namespace application\config;

class RouteMap extends \vsf\route\RouteMap {

    protected $routes = [
        '' => 'index/index',
        'index' => 'index/index',
        'index/index' => 'index/index',
    ];

}
