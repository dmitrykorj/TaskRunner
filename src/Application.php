<?php

namespace dmitrykorj\Taskrunner;

use dmitrykorj\Taskrunner\tasks\AbstractTask;

class Application
{
    const TASK_FILENAME_SUFFIX = 'Task.php';

    const ACTION = 'action';

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

        if (null === $args) {
            $args = $_SERVER['argv'];
        }

        Helper::arrayShift($args);

        $options = [];
        $params = [];

        foreach ($args as $arg) {
            if (strncmp($arg, '--', 2) === 0) {
                list($optionKey, $optionValue) = explode('=', substr($arg, 2), 2);
                if (!array_key_exists($optionKey, $options)) {
                    $options[$optionKey] = $optionValue;
                } else {
                    if (!is_array($options[$optionKey])) {
                        $options[$optionKey] = [$options[$optionKey]];
                    }
                    $options[$optionKey][] = $optionValue;
                }
            } else {
                $params[] = $arg;
            }
        }

        $taskName = Helper::arrayShift($params);

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
            if(!empty($options)) {
                foreach ($options as $key => $value) {
                    $object->{$key} = $value;
                }
            }
            return $object;
        }
        else
        {
            print_r('Таск <'. $className .'> не найден'."\n");
            return false;
        }
    }

    /**
     * @param $args
     * @throws Exception
     */
    private function resolveTask($args) // Название
    {
        list($route[0], $params, $options) = $this->parseArgs($args);

        if (stripos($route[0],':') !== false) {
            $route = explode(':', $route[0]);
        }

        if (($task = $this->createInstance(strtolower($route[0]), $options))
            && $task instanceof AbstractTask)
        {
            $action = $task->defaultAction;
            if (!empty($route[1])) {
                $action = $route[1];
            }
            $action = ucfirst($action);
            if (!method_exists($task, self::ACTION . $action)) {
                throw new Exception("Command {$action} not found.");
            }
            return call_user_func_array([$task, self::ACTION . $action], $params);
        }
    }
}