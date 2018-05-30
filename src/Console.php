<?php

namespace dmitrykorj\Taskrunner;

/**
 * Class Console
 * @package dmitrykorj\Taskrunner
 */
class Console
{
    /**
     * Выводит сообщение в консоль.
     *
     * @param $message
     * @param bool $linebreak
     */
    public static function write($message, $linebreak = false) {
        echo ($linebreak) ? $message.PHP_EOL : $message;
    }
}