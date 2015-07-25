<?php

namespace application\controllers;

class IndexController extends \vsf\Controller {

    public function index() {
        
        $test = new \application\models\Test();
        $observer = new \vsf\ModelObserver();
        $test->addObserver($observer);
        
        echo $this->response->success('SUCCESS', $this->params);
    }

    public function testSuccess() {
        echo $this->response->success('SUCCESS', $this->params);
    }

    public function testError() {
        echo $this->response->error('ERROR', $this->params);
    }

}
