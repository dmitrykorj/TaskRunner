<?php

namespace dmitrykorj\Taskrunner;

class Application
{
    const TASKPATH = '/tasks/';

    private static $_instance;

    private $defaultTask = 'help';

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function run()
    {
        $taskname = ucfirst($this->parseArgs()) . 'Task';
        $class = __NAMESPACE__ . '\\tasks\\' . ucfirst($this->fixName($taskname));
        if ($task = $this->createInstance($class)){
            $task->action();
        }

    }

    private function parseArgs($args = null)
    {

        if (null === $args) {
            $args = $_SERVER['argv'];
        }

        array_shift($args);

        if (!empty($args)) {
            return $args[0];
        }

        return $this->defaultTask;

    }

    private function fixName($var) {

        return preg_replace("/-/", '', $var);
    }

    private function createInstance($className) {
        if(class_exists($className)) {
            return new $className();
        }
        {
            echo ("Таск не найден\n");
            return false;
        }
    }
}