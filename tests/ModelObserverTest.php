<?php

class ModelObserverTest extends PHPUnit_Framework_TestCase {

    function testModelCreate() {
        $this->assertInstanceOf('vsf\observer\ModelObserver', new vsf\observer\ModelObserver);
    }

    function testUpdate() {
        $m = new vsf\observer\ModelObserver;
        $channel = 'Test Channel';
        $subject = 'Test Subject';
        $data = [];
        $this->assertTrue($m->update($channel, $subject, $data));
    }
    
    

}
