<?php

namespace application\controllers;

class IndexController extends \vsf\Controller {
    
    public function index() {
        
        $test = new \application\models\Test();
        $observer = new \vsf\ModelObserver();
        $test->addObserver($observer);
       
        var_dump($this->params);
        
        $test->create();
        
        $test->loadFirst();
       
        echo $this->response->success('SUCCESS', $test->getData());
    }

    public function testSuccess() {
        echo $this->response->success('SUCCESS', $this->params);
    }

    public function testError() {
        echo $this->response->error('ERROR', $this->params);
    }

}
