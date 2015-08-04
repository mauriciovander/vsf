<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Abstract Product:
interface Response {

    public function __construct($message = null, $data = null, $template = null);

    public function __toString();
}
