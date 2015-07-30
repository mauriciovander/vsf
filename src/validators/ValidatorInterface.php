<?php

namespace vsf\validators;

interface ValidatorInterface {

    public function validate(\stdClass $params = null);

    public function getMessage();
}
