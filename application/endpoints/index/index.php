<?php

namespace application\endpoints\index;

class index implements \vsf\EndpointInterface {

    use \vsf\EndpointTrait;

    protected $observers = [];
//    protected $validators = ['\\vsf\\validators\\SessionValidator'];
    protected $validators = [];
    protected $requided_params = [];

}
