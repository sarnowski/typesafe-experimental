<?php

/**
 * Dispatches an incoming request to the REST definitions.
 *
 * @name dispatcher
 * @author Tobias Sarnowski
 */
class RestDispatcher {

    /**
     * @inject extension RestExtension
     * @var RestExtension
     */
    var $restExtension;

    /**
     * @inject
     * @var MobManager
     */
    var $mobManager;

    /**
     * @inject
     * @var EventManager
     */
    var $eventManager;

    /**
     * Dispatches an incoming request.
     *
     * @return void
     */
    public function dispatch() {
        $methods = $this->restExtension->getMethods($_SERVER['REQUEST_METHOD']);
        foreach ($methods as $method) {
            if (preg_match('#'.$method['path'].'#', $_SERVER['REQUEST_URI'], $matches)) {
                $instance = $this->mobManager->getMob($method['mob']);
                $this->eventManager->fire('request.before', $method);
                $method['method']->invokeArgs($instance, $matches);
                $this->eventManager->fire('request.after');
                return;
            }
        }
    }

    function __toString() {
        return 'RestDispatcher';
    }


}
