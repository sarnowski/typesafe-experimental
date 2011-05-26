<?php

/**
 * Dispatches a request.
 *
 * @Name
 * @author Tobias Sarnowski
 */
class Dispatcher {

    /**
     * @inject
     * @var MobManager
     */
    var $mobManager;

    /**
     * Handle a request with $_SERVER.
     *
     * @return void
     */
    public function dispatch() {
        debug($_SERVER);
    }

}
