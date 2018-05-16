#!/usr/bin/php

<?php

require_once('vendor/autoload.php');

use dmitrykorj\Taskrunner\Application;

$app = Application::getInstance();
$app->registerTasks();
?>