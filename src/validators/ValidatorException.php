<?php

namespace vsf\validators;

class ValidatorException extends \Exception {

    public function __construct($message = null, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
