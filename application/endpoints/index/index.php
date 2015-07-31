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
        // from http://php.net/manual/es/function.filter-input-array.php
        $param_definition = [
            'id' => \FILTER_SANITIZE_ENCODED,
            'amount' => [
                'filter' => \FILTER_VALIDATE_FLOAT,
                'options' => [
                    'min_range' => 1,
                    'max_range' => 10
                ]
            ],
            'quantity' => [
                'filter' => \FILTER_VALIDATE_INT,
                'flags' => \FILTER_REQUIRE_SCALAR,
            ],
            'version' => \FILTER_SANITIZE_ENCODED,
        ];
        return $param_definition;
    }

    /**
     * @return array
     */
    public function getValidContexts() {
//        return [\vsf\Context::API];
        return [\vsf\Context::SITE, \vsf\Context::CLI];
    }

}
