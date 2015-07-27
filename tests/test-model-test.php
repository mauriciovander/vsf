<?php

class TestModelTest extends PHPUnit_Framework_TestCase {

    public function testCRUD() {
        $a = new application\models\Test();
        $a->create();

        $this->assertEquals(true, is_numeric($a->id_test));
        
        $b = new application\models\Test();
        $b->load($a->id_test);

        // Assert
        $this->assertEquals($a->id_test, $b->id_test);

        $c = new application\models\Test();
        $c->create();
        
        $this->assertLessThan(intval($c->id_test), intval($b->id_test));
        
    }

}
