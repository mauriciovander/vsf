<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

use application\config\URL as config;

class Context {

    const API = 'Api';
    const AJAX = 'Ajax';
    const SITE = 'Site';
    const CLI = 'Cli';

    public function getContext() {
        if ($this->isAjax()) {
            return self::AJAX;
        } else if ($this->isSite()) {
            return self::SITE;
        } else if ($this->isApi()) {
            return self::API;
        } else if ($this->isCli()) {
            return self::CLI;
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
