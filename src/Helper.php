<?php

namespace dmitrykorj\Taskrunner;

/**
 * Class Helper
 * @package dmitrykorj\Taskrunner
 */
class Helper
{
    /**
     * @param $array
     * @return mixed
     */
    public static function arrayShift(&$array)
    {
        $element = array_shift($array);
        $array = array_values($array);

        return $element;
    }
}
