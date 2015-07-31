<?php

namespace vsf\validators;

class SessionValidator implements \vsf\validators\ValidatorInterface {

    public function validate(\stdClass $params = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    public function getMessage() {
        return 'Invalid session';
    }

}
