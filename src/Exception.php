<?php

namespace dmitrykorj\Taskrunner;

class Exception extends \Exception
{
    /**
     * @param $file
     * @throws Exception
     */
    public static function fileAlreadyExist($file) {
        throw new Exception('Файл '. $file . ' уже используется!'.PHP_EOL);
    }

    /**
     * @throws Exception
     */
    public static function isTemplateNotFound() {
        throw new Exception('Файл шаблона не найден'.PHP_EOL);
    }
}