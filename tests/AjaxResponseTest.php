<?php

class AjaxResponseTest extends PHPUnit_Framework_TestCase {

    function testAjaxResponse() {
        $this->assertInstanceOf('vsf\response\AjaxResponse', new vsf\response\AjaxResponse);
    }

    function testSuccessInstanceType() {
        $response = new vsf\response\AjaxResponse;
        $this->assertInstanceOf('vsf\response\AjaxSuccessResponse', $response->success());
    }

    function testErrorInstanceType() {
        $response = new vsf\response\AjaxResponse;
        $this->assertInstanceOf('vsf\response\AjaxErrorResponse', $response->error());
    }

    function testSuccessResponseString() {
        $response = new vsf\response\AjaxResponse;
        $message = 'TEST';
        $data = [1, 2, 3];

        $expected1 = '{"result":"success"}';
        $this->assertEquals($expected1, strval($response->success()));

        $expected2 = '{"result":"success","message":"TEST"}';
        $this->assertEquals($expected2, strval($response->success($message)));

        $expected3 = '{"result":"success","message":"TEST","data":[1,2,3]}';
        $this->assertEquals($expected3, strval($response->success($message, $data)));
    }

    function testErrorResponseString() {
        $response = new vsf\response\AjaxResponse;
        $message = 'TEST';
        $data = [1, 2, 3];

        $expected1 = '{"result":"error"}';
        $this->assertEquals($expected1, strval($response->error()));

        $expected2 = '{"result":"error","message":"TEST"}';
        $this->assertEquals($expected2, strval($response->error($message)));

        $expected3 = '{"result":"error","message":"TEST","data":[1,2,3]}';
        $this->assertEquals($expected3, strval($response->error($message, $data)));
    }

}
