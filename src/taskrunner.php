#!/usr/bin/php

<?php
define("TASKSPATH", __DIR__ . '/tasks/');
require_once __DIR__.'/Application.php';

$app = Application::getInstance();
$app->run();
?>