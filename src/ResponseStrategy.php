<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

// Abstract Product:
// JSON response
interface Response {

    public function __construct($message = null, $data = null, $template = null);

    public function __toString();
}

// Abstract Factory:
interface ResponseFactory {

    public function error($message = null, $data = null);

    public function success($message = null, $data = null);

    public function setHeaders();

    public function setTemplate($template);
}

// Concrete Factory:
// Response for APIcontext
class ApiResponse implements ResponseFactory {

    private $template;

    public function error($message = null, $data = null) {
        return new ApiErrorResponse($message, $data);
    }

    public function success($message = null, $data = null) {
        return new ApiSuccessResponse($message, $data);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Content-Description: API response");
        header('Content-type: application/json; charset=utf-8');
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

}

// Concrete Factory
// Response for AJAX context
class AjaxResponse implements ResponseFactory {

    private $template;

    public function error($message = null, $data = null) {
        return new AjaxErrorResponse($message, $data, $this->template);
    }

    public function success($message = null, $data = null) {
        return new AjaxSuccessResponse($message, $data, $this->template);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Content-Description: AJAX response");
        header('Content-type: application/json; charset=utf-8');
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

}

// Concrete Factory
// Response for AJAX context
class SiteResponse implements ResponseFactory {

    private $template;

    public function error($view = null, $data = null) {
        return new SiteErrorResponse($view, $data, $this->template);
    }

    public function success($view = null, $data = null) {
        return new SiteSuccessResponse($view, $data, $this->template);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Description: Site response");
        header('Content-type: text/html');
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

}

// Concrete Factory
// Response for AJAX context
class CliResponse implements ResponseFactory {

    private $template;

    public function error($message = null, $data = null) {
        return new CliErrorResponse($message, $data, $this->template);
    }

    public function success($message = null, $data = null) {
        return new CliSuccessResponse($message, $data, $this->template);
    }

    public function setHeaders() {
// NOOP
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

}

abstract class JsonResponse {

    protected $result;
    protected $message;
    protected $data;
    protected $template;

    public function __construct($message = null, $data = null, $template = null) {
        $this->message = $message;
        $this->template = $template;
        $this->data = $data;
        $this->result = null;
    }

    public function __toString() {
        $response = new \stdClass();
        if (!is_null($this->result)) {
            $response->result = $this->result;
        }
        if (!is_null($this->message)) {
            $response->message = $this->message;
        }
        if (!is_null($this->data)) {
            $response->data = $this->data;
        }
        return json_encode($response);
    }

}

class JsonSuccessResponse extends JsonResponse {

    public function __construct($message = null, $data = null, $template = null) {
        parent::__construct($message, $data, $template);
        $this->result = 'success';
    }

}

class JsonErrorResponse extends JsonResponse {

    public function __construct($message = null, $data = null, $template = null) {
        parent::__construct($message, $data, $template);
        $this->result = 'error';
    }

}

// Concrete Product:
// Error response for API context
class ApiErrorResponse extends JsonErrorResponse implements Response {
    
}

// Concrete Product:
// Successful response for API context
class ApiSuccessResponse extends JsonSuccessResponse implements Response {
    
}

// Concrete Product:
// Error response for AJAX context
class AjaxErrorResponse extends JsonErrorResponse implements Response {
    
}

// Concrete Product:
// Successful response for AJAX context
class AjaxSuccessResponse extends JsonSuccessResponse implements Response {
    
}

// Concrete Product:
// Error response for Site context
class SiteErrorResponse implements Response {

    private $message;
    private $data;
    private $template;

    public function __construct($message = null, $data = null, $template = null) {
        $this->data = $data;
        $this->mmessage = $message;
        $this->template = $template;
    }

    public function __toString() {
        if (!is_null($this->template)) {
            $message = $this->message;
            foreach ($this->data as $key => $value) {
                $$key = $value;
            }
            include BASEPATH . '/application/views/' . $this->template;
        } else {
            var_dump($this->data);
        }
        return '';
    }

}

// Concrete Product:
// Successful response for Site context
class SiteSuccessResponse implements Response {

    private $message;
    private $data;
    private $template;

    public function __construct($message = null, $data = null, $template = null) {
        $this->data = $data;
        $this->message = $message;
        $this->template = $template;
    }

    public function __toString() {
        if (!is_null($this->template)) {
            $message = $this->message;
            foreach ($this->data as $key => $value) {
                $$key = $value;
            }
            include BASEPATH . '/application/views/' . $this->template;
        } else {
            echo $this->message;
            var_dump($this->data);
        }
        return '';
    }

}

// Concrete Product:
// Error response for CLI context
class CliErrorResponse extends JsonErrorResponse implements Response {
    
}

// Concrete Product:
// Successful response for CLI context
class CliSuccessResponse extends JsonSuccessResponse implements Response {
    
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
                $this->response_strategy = new AjaxResponse;
                break;
            case Context::API:
                $this->response_strategy = new ApiResponse;
                break;
            case Context::CLI:
                $this->response_strategy = new CliResponse($cliArguments);
                break;
            case Context::SITE:
                $this->response_strategy = new SiteResponse;
                break;
        }
    }

    public function getResponse() {
        return $this->response_strategy;
    }

}
