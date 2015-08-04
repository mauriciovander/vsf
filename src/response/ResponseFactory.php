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
