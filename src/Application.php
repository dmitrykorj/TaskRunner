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
        if (null === self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function registerTasks()
    {
        $directoryIterator = new \DirectoryIterator(__DIR__ . '/tasks');
        foreach ($directoryIterator as $fileinfo)
        {
            if (!$fileinfo->isDot())
            {
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
        try {
            $this->resolveTask($args);
            exit(0);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }

    /**
     * @param null $args
     * @return array
     */
    public function parseArgs($args = null)
    {

        if (null === $args)
        {
            $args = $_SERVER['argv'];
        }

        Helper::arrayShift($args);
        $taskName = Helper::arrayShift($args);

        $options = [];
        $params = [];

        foreach ($args as $arg) {
            if (strncmp($arg, '--', 2) === 0) {
                $options[] = substr($arg, 2);
            } else {
                $params[] = $arg;
            }
        }

        if ($taskName)
        {
            return [$taskName, $params, $options];
        }
        else
        {
            return [$this->defaultTask, null, null];
        }
    }

    private function createInstance($className, $options = [])
    {
        if (array_key_exists($className, $this->_registeredTasks))
        {
            $object = new $this->_registeredTasks[$className];
            foreach ($options as $key => $value) {// ["foo=bar"], ["foo" => "bar"]
                $object->{$key} = $value;
            }

            return $object;
        }
        else
        {
            print_r('Таск <'. $className .'> не найден'."\n");
            return false;
        }
    }

    private function resolveTask($args) // Название
    {
        var_dump(list($route, $params, $options) = $this->parseArgs($args));

        if (($task = $this->createInstance((strtolower($route)), $options))
            && $task instanceof AbstractTask)
        {
            if (!empty($params))
            {
                $method = method_exists($task, ($params[0]));
                    if ($method)
                    {
                        $task->{$params[0]}($params);
                    }

                    {
                        $task->action($params);
                    }
            }
            else
            {
                $task->action($params);
            }
        }
    }
}