<?php

namespace vsf\route;

class RouteMap {

    public function getRoute($route) {
        if (array_key_exists($route, $this->routes)) {
            return $this->routes[$route];
        } else {
            return reset($this->routes);
        }
    }

}
