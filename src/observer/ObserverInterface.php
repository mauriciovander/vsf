<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\observer;

/**
 *  Observer Interface
 */
interface ObserverInterface {

    /**
     * @param string $channel
     * @param string $subject
     * @param mixed $data
     */
    public function update($channel, $subject, $data);
}
