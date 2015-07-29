<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// This class is used as a template for other specific response types.
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
