<?php

namespace vsf\response;

class JsonErrorResponse extends JsonResponse {

    public function __construct($message = null, $data = null, $template = null) {
        parent::__construct($message, $data, $template);
        $this->result = 'error';
    }

}
