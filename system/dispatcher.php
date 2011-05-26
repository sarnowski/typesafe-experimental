<?php
/**
 * Bootstraps the framework.
 *
 * @author Tobias Sarnowski
 */

require('core/debug.php');
require('core/manager/impl/MobManagerImpl.php');

$BASEDIR = dirname(dirname(__FILE__));

$manager = MobManagerImpl::create();
$manager->addDirectory("$BASEDIR/system/core/events");
$manager->addDirectory("$BASEDIR/system/core/injection");
$manager->addDirectory("$BASEDIR/system/classes");
$manager->addDirectory("$BASEDIR/classes");
$manager->start();

try {
    $dispatcher = $manager->getMob('dispatcher');
    $dispatcher->dispatch();
} catch (Exception $e) {
    debug($e);
}

$manager->stop();
