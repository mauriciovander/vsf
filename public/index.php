<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

require __DIR__ . '/../vendor/autoload.php';

// Some useful constants definnitions:
define('BASEPATH',  realpath(__DIR__.'/..'));
define('UUID', uniqid(true));

$context = new \vsf\Context();

if (isset($argc)) {
    $params = $argv;
} else {
    $params = array();
}

$app = new \vsf\Application($context->getContext(), $params);
