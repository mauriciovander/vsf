<?php

namespace application\controllers;

class IndexController extends \Vsf\Controller {

    public function index() {
        echo $this->response->success('SUCCESS', $this->params);
    }

        public function testSuccess() {
        echo $this->response->success('SUCCESS', $this->params);
    }
        public function testError() {
        echo $this->response->error('ERROR', $this->params);
    }

}
