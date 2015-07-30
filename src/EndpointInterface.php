<?php

namespace vsf;

trait EndpointTrait {

    public function getObservers() {
        return $this->observers;
    }

    public function getValidators() {
        return $this->validators;
    }

    public function getRequiredParams() {
        return $this->requided_params;
    }

}

interface EndpointInterface {

    public function getValidators();

    public function getObservers();
}
