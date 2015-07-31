<?php

namespace vsf\response;

class JsonSuccessResponse extends JsonResponse {

    public function __construct($message = null, $data = null, $template = null) {
        parent::__construct($message, $data, $template);
        $this->result = 'success';
    }

}
