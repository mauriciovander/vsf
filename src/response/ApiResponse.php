<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Concrete Factory:
// Response for APIcontext
class ApiResponse implements ResponseInterface {

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

// Concrete Product:
// Error response for API context
class ApiErrorResponse extends JsonErrorResponse implements Response {
    
}

// Concrete Product:
// Successful response for API context
class ApiSuccessResponse extends JsonSuccessResponse implements Response {
    
}
