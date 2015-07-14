<?php

namespace App;

// Abstract Product:
// JSON response
interface Response {
    public function __construct($message = null, $data = null);
    public function __toString();
}
// Abstract Factory:
interface ResponseFactory {
    public function error($message = null, $data = null);
    public function success($message = null, $data = null);
    public function setHeaders();
}
// Concrete Factory:
// Response for APIcontext
class ApiResponse implements \App\ResponseFactory {
    public function error($message = null, $data = null) {
        return new \App\ApiErrorResponse($message, $data);
    }
    public function success($message = null, $data = null) {
        return new \App\ApiSuccessResponse($message, $data);
    }
    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Content-Description: API response");
        header('Content-type: application/json; charset=utf-8');
    }
}
// Concrete Factory
// Response for AJAX context
class AjaxResponse implements \App\ResponseFactory {
    public function error($message = null, $data = null) {
        return new \App\AjaxErrorResponse($message, $data);
    }
    public function success($message = null, $data = null) {
        return new \App\AjaxSuccessResponse($message, $data);
    }
    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Content-Description: AJAX response");
        header('Content-type: application/json; charset=utf-8');
    }
}
// Concrete Factory
// Response for AJAX context
class SiteResponse implements \App\ResponseFactory {
    public function error($message = null, $data = null) {
        return new \App\SiteErrorResponse($message, $data);
    }
    public function success($message = null, $data = null) {
        return new \App\SiteSuccessResponse($message, $data);
    }
    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Description: Site response");
        header('Content-type: text/html');
    }
}
// Concrete Factory
// Response for AJAX context
class CliResponse implements \App\ResponseFactory {
    public function error($message = null, $data = null) {
        return new \App\CliErrorResponse($message, $data);
    }
    public function success($message = null, $data = null) {
        return new \App\CliSuccessResponse($message, $data);
    }
    public function setHeaders() {
// NOOP
    }
}
abstract class JsonResponse {
    private $message;
    private $data;
    public function __construct($message = null, $data = null) {
        $this->message = $message;
        $this->data = $data;
    }
    public function __toString() {
        $response = new \stdClass();
        $response->result = 'error';
        if (!is_null($this->message)) {
            $response->message = $this->message;
        }
        if (!is_null($this->data)) {
            $response->data = $this->data;
        }
        return json_encode($response);
    }
}
// Concrete Product:
// Error response for API context
class ApiErrorResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Successful response for API context
class ApiSuccessResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Error response for AJAX context
class AjaxErrorResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Successful response for AJAX context
class AjaxSuccessResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Error response for AJAX context
class SiteErrorResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Successful response for AJAX context
class SiteSuccessResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Error response for CLI context
class CliErrorResponse extends \App\JsonResponse implements Response {
    
}
// Concrete Product:
// Successful response for CLI context
class CliSuccessResponse extends \App\JsonResponse implements Response {
    
}
class Context {
    const API = 1;
    const AJAX = 2;
    const SITE = 3;
    const CLI = 4;
    public function getContext() {
        if ($this->isAjax()) {
            return self::AJAX;
        } else if ($this->isApi()) {
            return self::API;
        } else if ($this->isCli()) {
            return self::CLI;
        } else {
            return self::SITE;
        }
    }
    private function isAjax() {
        $http_x_req_with_filter = \filter_input(INPUT_SERVER, HTTP_X_REQUESTED_WITH);
        $http_x_req_with = \strtolower($http_x_req_with_filter);
        return !empty($http_x_req_with) && $http_x_req_with == 'xmlhttprequest';
    }
    private function isApi() {
        $host = \filter_input(INPUT_SERVER, 'HTTP_HOST');
        return \strpos($host, 'local.api') === 0;
    }
    private function isCli() {
        return \php_sapi_name() == "cli";
    }
}
// Strategy:
class ResponseStrategy {
    private $response_strategy;
    private $context;
    public function __construct($context, $cliArguments = null) {
        $this->context = $context;
        $this->selectResponseStrategyFromContext($cliArguments);
    }
    private function selectResponseStrategyFromContext($cliArguments) {
        switch ($this->context) {
            case Context::AJAX:
                $this->response_strategy = new \App\AjaxResponse;
                break;
            case Context::API:
                $this->response_strategy = new \App\ApiResponse;
                break;
            case Context::CLI:
                $this->response_strategy = new \App\CliResponse($cliArguments);
                break;
            case Context::SITE:
                $this->response_strategy = new \App\SiteResponse;
                break;
        }
    }
    public function getResponse() {
        return $this->response_strategy;
    }
}
interface RouteInterface {
    public function getController();
    public function getAction();
    public function getParams();
    public function validateAndLoad();
}
abstract class Route {
    private $controller;
    private $action;
    private $params;
    public function validateAndLoad() {
        
    }
}
class CliRoute extends Route implements \App\RouteInterface {
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
        return $this->controller;
    }
    public function getAction() {
        return $this->action;
    }
    public function getParams() {
        return $this->params;
    }
}
class AjaxRoute extends Route implements \App\RouteInterface {
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
        return $this->controller;
    }
    public function getAction() {
        return$this->action;
    }
    public function getParams() {
        return $this->params;
    }
}
class SiteRoute extends Route implements \App\RouteInterface {
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
        return $this->controller;
    }
    public function getAction() {
        return$this->action;
    }
    public function getParams() {
        return $this->params;
    }
}
class ApiRoute extends Route implements \App\RouteInterface {
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
        return $this->controller;
    }
    public function getAction() {
        return$this->action;
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
                $this->route_strategy = new \App\AjaxRoute;
                break;
            case Context::API:
                $this->route_strategy = new \App\ApiRoute;
                break;
            case Context::CLI:
                $this->route_strategy = new \App\CliRoute($argv);
                break;
            case Context::SITE:
                $this->route_strategy = new \App\SiteRoute;
                break;
        }
    }
    public function getRoute() {
        return $this->route_strategy;
    }
}
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
    private function run() {
        
    }
    private function setRouteFromContext($argv) {
        $route_strategy = new \App\RouteStrategy($this->context, $argv);
        $this->route = $route_strategy->getRoute();
        echo $this->route->getController();
        echo $this->route->getAction();
        echo json_encode($this->route->getParams());
    }
    private function checkParams() {
        $this->params = null;
    }
    private function selectResponseFromContext() {
        $response_strategy = new \App\ResponseStrategy($this->context);
        $this->response = $response_strategy->getResponse();
        $this->response->setHeaders();
    }
    public function testSuccess() {
        echo $this->response->success('GOL', array('a' => 3));
    }
    public function testError() {
        echo $this->response->error('Penal!');
    }
}
$context = new \App\Context;
$app = new \App\Application($context->getContext(), $argv);
//$app->testSuccess();
//echo PHP_EOL;
//$app->testError();
//echo PHP_EOL;
