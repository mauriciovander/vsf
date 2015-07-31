<?php

namespace vsf;

interface EndpointInterface {

    public function getValidators();

    public function getObservers();

    public function getRequiredParams();

    public function getValidContexts();
}
