<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Response Factory:
class ResponseFactory {

    public static function create($context) {
        $factory = '\\vsf\\response\\' . \ucwords($context) . 'Response';
        return new $factory;
    }

}

// Abstract Product:
// JSON response
interface Response {

    public function __construct($message = null, $data = null, $template = null);

    public function __toString();
}

// Abstract Factory:
interface ResponseInterface {

    public function error($message = null, $data = null);

    public function success($message = null, $data = null);

    public function setHeaders();

    public function setTemplate($template);
}
