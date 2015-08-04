<?php

include __DIR__ . '/../vendor/autoload.php';

class TestApiResponse extends PHPUnit_Framework_TestCase {

    function testApiResponse() {
        $this->assertInstanceOf('vsf\response\ApiResponse', new vsf\response\ApiResponse);
    }

    function testSuccessInstanceType() {
        $response = new vsf\response\ApiResponse;
        $this->assertInstanceOf('vsf\response\ApiSuccessResponse', $response->success());
    }

    function testErrorInstanceType() {
        $response = new vsf\response\ApiResponse;
        $this->assertInstanceOf('vsf\response\ApiErrorResponse', $response->error());
    }

    function testSuccessResponseString() {
        $response = new vsf\response\ApiResponse;
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
        $response = new vsf\response\ApiResponse;
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
