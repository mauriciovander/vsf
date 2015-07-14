<?php

namespace Vsf;

class Context {
    const API = 1;
    const AJAX = 2;
    const SITE = 3;
    const CLI = 4;
    public function getContext() {
        if ($this->isAjax()) {
            return self::AJAX;
        } else if ($this->isApi()) {
            return self::API;
        } else if ($this->isCli()) {
            return self::CLI;
        } else {
            return self::SITE;
        }
    }
    private function isAjax() {
        $http_x_req_with_filter = \filter_input(INPUT_SERVER, HTTP_X_REQUESTED_WITH);
        $http_x_req_with = \strtolower($http_x_req_with_filter);
        return !empty($http_x_req_with) && $http_x_req_with == 'xmlhttprequest';
    }
    private function isApi() {
        $host = \filter_input(INPUT_SERVER, 'HTTP_HOST');
        return \strpos($host, 'local.api') === 0;
    }
    private function isCli() {
        return \php_sapi_name() == "cli";
    }
}