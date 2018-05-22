<?php

namespace dmitrykorj\Taskrunner;

use dmitrykorj\Taskrunner\tasks\AbstractTask;

class Application
{
    const TASK_FILENAME_SUFFIX = 'Task.php';

    private static $_instance;

    private $defaultTask = 'Help';

    private $_registeredTasks = [];

    public $_namespace = __NAMESPACE__;

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


            $this->createCopyFile();
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

    private function createInstance($className)
    {
        if (array_key_exists($className, $this->_registeredTasks)) {
            return new $this->_registeredTasks[$className];
        } else {
           // echo $this->_registeredTasks[$className];
            echo 'Таск <'. $className .'> не найден'."\n";
            return false;
        }
    }

    private function fixName($name) {

        return preg_replace("/-/", '', $name);
    }

    public function createCopyFile() {
        $file = __DIR__.'/templates/'.'Template.php';
        $newfile = __DIR__.'/tasks/'.'Task.php';
        copy($file, $newfile);
        $this->replace($newfile);
    }

    private function replace($file) {
        $replace = null;

        $replace_array1 = [
            "{TaskName}",
            "{NameSpace}",
        ];
        $replace_array2 = [
            'NewFile',
            $this->_namespace,
        ];

        for($i = 0; $i < 2; $i++) {
            $content_file = file_get_contents($file);
            $replace = str_replace($replace_array1[$i], $replace_array2[$i], $content_file);
            file_put_contents($file, $replace);
        }
    }
}