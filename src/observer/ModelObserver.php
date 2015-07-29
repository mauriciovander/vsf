<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\observer;

class ModelObserver implements ObserverInterface {

    public function update($channel, $subject, $data) {
        $log = new \Monolog\Logger($channel);
        $log->addNotice($subject, $data);
    }

}
