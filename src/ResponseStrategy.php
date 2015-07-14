<?php

namespace Vsf;

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
class ApiResponse implements \Vsf\ResponseFactory {

    public function error($message = null, $data = null) {
        return new \Vsf\ApiErrorResponse($message, $data);
    }

    public function success($message = null, $data = null) {
        return new \Vsf\ApiSuccessResponse($message, $data);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Content-Description: API response");
        header('Content-type: application/json; charset=utf-8');
    }

}

// Concrete Factory
// Response for AJAX context
class AjaxResponse implements \Vsf\ResponseFactory {

    public function error($message = null, $data = null) {
        return new \Vsf\AjaxErrorResponse($message, $data);
    }

    public function success($message = null, $data = null) {
        return new \Vsf\AjaxSuccessResponse($message, $data);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Content-Description: AJAX response");
        header('Content-type: application/json; charset=utf-8');
    }

}

// Concrete Factory
// Response for AJAX context
class SiteResponse implements \Vsf\ResponseFactory {

    public function error($message = null, $data = null) {
        return new \Vsf\SiteErrorResponse($message, $data);
    }

    public function success($message = null, $data = null) {
        return new \Vsf\SiteSuccessResponse($message, $data);
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
class CliResponse implements \Vsf\ResponseFactory {

    public function error($message = null, $data = null) {
        return new \Vsf\CliErrorResponse($message, $data);
    }

    public function success($message = null, $data = null) {
        return new \Vsf\CliSuccessResponse($message, $data);
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
class ApiErrorResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Successful response for API context
class ApiSuccessResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Error response for AJAX context
class AjaxErrorResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Successful response for AJAX context
class AjaxSuccessResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Error response for AJAX context
class SiteErrorResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Successful response for AJAX context
class SiteSuccessResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Error response for CLI context
class CliErrorResponse extends \Vsf\JsonResponse implements Response {
    
}

// Concrete Product:
// Successful response for CLI context
class CliSuccessResponse extends \Vsf\JsonResponse implements Response {
    
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
                $this->response_strategy = new \Vsf\AjaxResponse;
                break;
            case Context::API:
                $this->response_strategy = new \Vsf\ApiResponse;
                break;
            case Context::CLI:
                $this->response_strategy = new \Vsf\CliResponse($cliArguments);
                break;
            case Context::SITE:
                $this->response_strategy = new \Vsf\SiteResponse;
                break;
        }
    }

    public function getResponse() {
        return $this->response_strategy;
    }

}
