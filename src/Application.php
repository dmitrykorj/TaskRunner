<?php

namespace dmitrykorj\Taskrunner;

use dmitrykorj\Taskrunner\tasks\AbstractTask;

class Application
{
    const TASK_FILENAME_SUFFIX = 'Task.php';

    private static $_instance;

    public $defaultTask = 'Help';

    private $_registeredTasks = [];

    public static $_namespace = __NAMESPACE__;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function registerTasks()
    {
        $directoryIterator = new \DirectoryIterator(__DIR__ . '/tasks');
        foreach ($directoryIterator as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $fileinfo->current();
                $fileName = $fileinfo->getFilename();
                $className = __NAMESPACE__ . '\\tasks\\' . preg_replace('/.php/','', $fileinfo->getFilename());
                $this->addTask(strtolower(str_replace(self::TASK_FILENAME_SUFFIX, '', $fileName)), $className);
            }
        }
    }

    public function addTask($id,$className)
    {
        $this->_registeredTasks[$id] = $className;
    }

    public function run($args = null)
    {
        $this->registerTasks();
        if (
            ($task = $this->createInstance((strtolower($this->fixName($this->parseArgs($args))))))
            && $task instanceof AbstractTask
        ){
            $task->action();
        }
    }

    public function parseArgs($args = null)
    {

        if (null === $args) {
            $args = $_SERVER['argv'];
        }

        array_shift($args);
        if (!empty($args) && count($args) <= 1) {
            return $args[0];
        }
        //if (!empty($args) && count($args) >= 2) {
        //    return $args;
        //}
        else {
            return $this->defaultTask;
        }


    }

    private function createInstance($className)
    {
        if (array_key_exists($className, $this->_registeredTasks)) {
            return new $this->_registeredTasks[$className];
        } else {
            echo 'Таск <'. $className .'> не найден'."\n";
            return false;
        }
    }

    private function fixName($name) {
        return preg_replace("/-/", '', $name);
    }
}