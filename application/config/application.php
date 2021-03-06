<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace application\config;

abstract class Application {

    const NAME = 'Very Simple Framework';
    
    /**
     * SITE default route
     */
    const DEFAULT_SITE_CONTROLLER = 'index';
    const DEFAULT_SITE_ACTION = 'index';

    /**
     * API default route
     */
    const DEFAULT_API_CONTROLLER = 'index';
    const DEFAULT_API_ACTION = 'index';

    /**
     * CLI default route
     */
    const DEFAULT_CLI_CONTROLLER = 'index';
    const DEFAULT_CLI_ACTION = 'index';

    /**
     * AJAX default route
     */
    const DEFAULT_AJAX_CONTROLLER = 'index';
    const DEFAULT_AJAX_ACTION = 'index';

}
