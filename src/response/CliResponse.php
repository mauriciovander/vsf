<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Concrete Factory
// Response for AJAX context
class CliResponse implements ResponseInterface {

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

// Concrete Product:
// Error response for CLI context
class CliErrorResponse extends JsonErrorResponse implements Response {
    
}

// Concrete Product:
// Successful response for CLI context
class CliSuccessResponse extends JsonSuccessResponse implements Response {
    
}
