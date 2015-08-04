<?php

include __DIR__ . '/../vendor/autoload.php';

class TestSiteResponse extends PHPUnit_Framework_TestCase {

    function testSiteResponse() {
        $this->assertInstanceOf('vsf\response\SiteResponse', new vsf\response\SiteResponse);
    }

    function testSuccessInstanceType() {
        $response = new vsf\response\SiteResponse;
        $this->assertInstanceOf('vsf\response\SiteSuccessResponse', $response->success());
    }

    function testErrorInstanceType() {
        $response = new vsf\response\SiteResponse;
        $this->assertInstanceOf('vsf\response\SiteErrorResponse', $response->error());
    }

    function testSuccessResponseString() {
        $response = new vsf\response\SiteResponse;
        $message = 'TEST';
        $data = [1, 2, 3];

        $expected1 = '{}';
        $this->assertEquals($expected1, strval($response->success()));

        $expected2 = '{"message":"TEST"}';
        $this->assertEquals($expected2, strval($response->success($message)));

        $expected3 = '{"message":"TEST","data":[1,2,3]}';
        $this->assertEquals($expected3, strval($response->success($message, $data)));
    }

    function testErrorResponseString() {
        $response = new vsf\response\SiteResponse;
        $message = 'TEST';
        $data = [1, 2, 3];

        $expected1 = '{}';
        $this->assertEquals($expected1, strval($response->error()));

        $expected2 = '{"message":"TEST"}';
        $this->assertEquals($expected2, strval($response->error($message)));

        $expected3 = '{"message":"TEST","data":[1,2,3]}';
        $this->assertEquals($expected3, strval($response->error($message, $data)));
    }

    function testErrorResponseStringWithTemplate() {
        $response = new vsf\response\SiteResponse;
        $message = 'TEST';
        $data = [1, 2, 3];

        $response->setTemplate('test-error.php');

        $expected1 = "ERROR";
        $this->assertEquals($expected1, strval($response->error()));

        $expected2 = "ERROR\nTEST";
        $this->assertEquals($expected2, strval($response->error($message)));

        $expected3 = "ERROR\nTEST[1,2,3]";
        $this->assertEquals($expected3, strval($response->error($message, $data)));
    }

    function testSuccessResponseStringWithTemplate() {
        $response = new vsf\response\SiteResponse;
        $message = 'TEST';
        $data = [1, 2, 3];

        $response->setTemplate('test-success.php');

        $expected1 = "SUCCESS";
        $this->assertEquals($expected1, strval($response->success()));

        $expected2 = "SUCCESS\nTEST";
        $this->assertEquals($expected2, strval($response->success($message)));

        $expected3 = "SUCCESS\nTEST[1,2,3]";
        $this->assertEquals($expected3, strval($response->success($message, $data)));
    }

}
