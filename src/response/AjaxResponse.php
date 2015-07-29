<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Concrete Factory
// Response for AJAX context
class AjaxResponse implements ResponseInterface {

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

// Concrete Product:
// Error response for AJAX context
class AjaxErrorResponse extends JsonErrorResponse implements Response {
    
}

// Concrete Product:
// Successful response for AJAX context
class AjaxSuccessResponse extends JsonSuccessResponse implements Response {
    
}
