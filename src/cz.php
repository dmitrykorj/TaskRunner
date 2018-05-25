<?php
$options = [];
$params = [];
$args = ['--config', 'yii','--reeue'];

foreach ($args as $arg) {
    if (strncmp($arg, '--', 2) === 0) {
        $options[] = substr($arg, 2);
    } else {
        $params[] = $arg;
    }
}

var_dump($params);
echo '------------------------------------'.PHP_EOL;
var_dump($options);