<?php

namespace application\endpoints\index;

class index implements \vsf\EndpointInterface {

    /**
     * @return array
     */
    public function getObservers() {
        return [];
    }

    /**
     * @return array
     */
    public function getValidators() {
//        return [new \vsf\validators\SessionValidator];
        return [];
    }

    /**
     * @return array
     */
    public function getRequiredParams() {
        return ['a'];
    }

    /**
     * @return array
     */
    public function getValidContexts() {
//        return [\vsf\Context::API];
        return [\vsf\Context::SITE, \vsf\Context::API];
    }

}
