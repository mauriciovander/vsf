<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace application\models;

use vsf;

/**
 * @name Test Model
 * Requires the creation of "test" table
 * CREATE TABLE `test` (
 * `id_test` int(11) NOT NULL AUTO_INCREMENT,
 * `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * PRIMARY KEY (`id_test`)
 * );
 */
class Test extends vsf\Model {
    
}
