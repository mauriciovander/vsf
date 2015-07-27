<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace application\controllers;

class IndexController extends \vsf\Controller {

    public function index() {

        $test = new \application\models\Test();
        $observer = new \vsf\ModelObserver();
        $test->addObserver($observer);

        $test->create();

//        $test->loadFirst();
        $this->response->setTemplate('index.php');
        
        echo $this->response->success('success', array(
            'model' => $test->getData(),
            'input' => $this->params));
    }

    public function testSuccess() {
        echo $this->response->success('SUCCESS', $this->params);
    }

    public function testError() {
        echo $this->response->error('ERROR', $this->params);
    }

}
