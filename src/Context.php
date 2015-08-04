<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

use application\config\URL as config;

/**
 * For each new context definition included in this class, 
 * new RouteInterface and ResponseInterface must be added 
 * in route and response folders respectively 
 * Example: a new FileResponse could be added to the options and a 
 * mixed context should be implemented... 
 */
class Context {

    const API = 'Api';
    const AJAX = 'Ajax';
    const SITE = 'Site';
    const CLI = 'Cli';

    public function getContext() {
        try {
            if ($this->isAjax()) {
                return self::AJAX;
            } else if ($this->isSite()) {
                return self::SITE;
            } else if ($this->isApi()) {
                return self::API;
            } else if ($this->isCli()) {
                return self::CLI;
            }
            throw new UnknownContextException;
        } catch (UnknownContextException $e) {
            var_dump($e->getTrace());
            exit;
        }
    }

    private function isAjax() {
        $http_x_req_with_filter = \filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');
        $http_x_req_with = \strtolower($http_x_req_with_filter);
        return !empty($http_x_req_with) && $http_x_req_with == 'xmlhttprequest';
    }

    private function isApi() {
        $host = \filter_input(INPUT_SERVER, 'HTTP_HOST');
        return \strpos($host, config::API) !== false;
    }

    private function isSite() {
        $host = \filter_input(INPUT_SERVER, 'HTTP_HOST');
        return (\strpos($host, config::SITE) === 0);
    }

    private function isCli() {
        return \php_sapi_name() == "cli";
    }

}

/**
 * Context Exception
 */
class UnknownContextException extends \Exception {

    public function __construct($message = null, $code = 404, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $log = new \Monolog\Logger('Context');
        $log->addError('Unknown context. ' . $message);
    }

}
