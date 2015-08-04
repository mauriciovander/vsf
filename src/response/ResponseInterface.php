<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Abstract Factory:
interface ResponseInterface {

    public function error($message = null, $data = null);

    public function success($message = null, $data = null);

    public function setHeaders();

    public function setTemplate($template);
}
