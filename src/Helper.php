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

    /**
     * @param $className
     * @return string
     */
    public static function getTaskClassName($className) {
        $className = self::getTaskNamespace() . ucfirst($className) . 'Task';
        return $className;
    }

    /**
     * @param $object
     * @return object
     */
    public static function getClassObject($object) {
        self::getTaskClassName($object);
        return new $object;
    }

    /**
     * @return string
     */
    protected static function getTaskNamespace()
    {
        return __NAMESPACE__ . '\\tasks\\';
    }
}
