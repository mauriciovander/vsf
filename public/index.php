<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require __DIR__ . '/../vendor/autoload.php';

$context = new Vsf\Context();

if ($argc) {
    $params = $argv;
} else {
    $params = array();
}

$app = new Vsf\Application($context->getContext(), $params);
