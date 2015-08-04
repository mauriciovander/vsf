<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf;

interface EndpointInterface {

    public function getValidators();

    public function getObservers();

    public function getRequiredParams();

    public function getValidContexts();
}
