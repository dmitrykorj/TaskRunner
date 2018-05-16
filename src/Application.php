<?php

namespace dmitrykorj\Taskrunner;

class Application
{
    const TASKPATH = '/tasks/';

    const TASK_FILENAME_SUFFIX = 'Task.php';

    private static $_instance;

    private $defaultTask = 'help';

    private $_registeredTasks = [];

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function registerTasks()
    {
        $directoryIterator = new \DirectoryIterator(__DIR__ . '/tasks');
        while($directoryIterator->valid()) {
            $file = $directoryIterator->current();
            $this->_registeredTasks[] = $file->getFilename();
            $directoryIterator->next();
        }
        var_dump($this->_registeredTasks);
        //$this->_registeredTasks['help'] = __NAMESPACE__ . '/tasks/' . $fileName;
        //$this->addTask('help', )
    }

    public function addTask($id, $className)
    {
        $this->_registeredTasks[$id] = $className;
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

        if (!empty($args) && count($args) == 1) {
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