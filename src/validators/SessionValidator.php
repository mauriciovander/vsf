<?php

namespace vsf\validators;

class SessionValidator implements \vsf\validators\ValidatorInterface {

    public function validate(\stdClass $params = null) {
        @session_start();
        return isset($_SESSION['user']);
    }

    public function getMessage() {
        return 'Invalid session';
    }

}
