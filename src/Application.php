<?php

namespace dmitrykorj\Taskrunner;

class Application
{
    const TASK_FILENAME_SUFFIX = 'Task.php';

    private static $_instance;

    private $defaultTask = 'Help';

    private $_registeredTasks = [];

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
                $className = __NAMESPACE__ . '\\tasks\\' . preg_replace('/.php/','',$fileinfo->getFilename());
                $this->addTask($fileName, $className);
            }
        }
    }

    public function addTask($id,$className)
    {
        $this->_registeredTasks[$id] = $className;
    }

    public function run()
    {
        $this->registerTasks();
        if ($task = $this->createInstance($this->getClassNameFromArgs())){
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

    private function fixName($name) {

        return preg_replace("/-/", '', $name);
    }

    private function createInstance($className)
    {
        if (array_key_exists($className, $this->_registeredTasks)) {
            return new $this->_registeredTasks[$className];
        } else {
            echo "Таск не найден\n";
            return false;
        }
    }

    private function getClassNameFromArgs()
    {
        $taskname = ucfirst($this->parseArgs()) . self::TASK_FILENAME_SUFFIX;
        $class = ucfirst($this->fixName($taskname));
        return $class;
    }
}