<?php

namespace dmitrykorj\Taskrunner;

use dmitrykorj\Taskrunner\tasks\AbstractTask;

/**
 * Class Application
 * @package dmitrykorj\Taskrunner
 */
class Application
{
    const TASK_FILENAME_SUFFIX = 'Task.php';

    const ACTION = 'action';

    private static $_instance;

    private $defaultTask = 'help';

    private $_registeredTasks = [];

    public static function getInstance()
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Проверяет на наличие доступных тасков в папке "/tasks" и добавляет их в массив
     */
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

    /**
     * Добавляет таск в массив c доступными тасками.
     *
     * @param $id
     * @param $className
     */
    public function addTask($id, $className)
    {
        $this->_registeredTasks[$id] = $className;
    }

    /**
     * Запуск консольного приложения.
     *
     * @param null $args
     * @throws \Exception
     */
    public function run($args = null)
    {
        $this->registerTasks();
        try {
            $this->resolveTask($args);
            exit(0);
        } catch (Exception $e) {
            Console::write($e->getMessage());
            exit(1);
        }
    }

    /**
     * Парсит переданные аргументы.
     * Возвращает массив с названием таска и переданными опциями и агрументами.
     *
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
                $optionValue = null;
                if (strpos($arg, '=') !== false) {
                    list($optionKey, $optionValue) = explode('=', substr($arg, 2), 2);
                } else {
                    $optionKey = substr($arg, 2);
                }
                if ($optionValue === null) {
                    $optionValue = true;
                }
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
            return [$this->defaultTask, [], null];
        }
    }


    /**
     * Проверка на наличие переданного аргумента на наличие в массиве с зарегистированными
     * тасками.
     * Возвращает объект класса переданного таска.
     *
     * @param $className
     * @param array $options
     * @return object
     * @throws \Exception
     */
    private function createInstance($className, $options = [])
    {
        if (array_key_exists($className, $this->_registeredTasks))
        {
            $object = Helper::getClassObject($this->_registeredTasks[$className]);
            if(!empty($options)) {
                foreach ($options as $key => $value) {
                    $object->{$key} = $value;
                }
            }
            return $object;
        }
        else
        {
            throw new Exception("Task $className isn't found");
        }
    }


    /**
     * Разбивает первый аргумент при наличии в нем символа ":" на массив
     * Проверяет на наличие указанного метода.
     * Возвращает вызор callback функции с указанным классом, экшеном и параметрами.
     *
     * @param $args
     * @return mixed
     * @throws Exception
     * @throws \Exception
     */
    private function resolveTask($args)
    {
        list($route[0], $params, $options) = $this->parseArgs($args);
        if (stripos($route[0], ':') !== false) {
            $route = explode(':', $route[0]);
        }

        if (($task = $this->createInstance(strtolower($route[0]), $options))
            && $task instanceof AbstractTask) {
            $action = $task->defaultAction;
            if (!empty($route[1])) {
                $action = $route[1];
            }

            $action = ucfirst($action);

            if (!method_exists($task, self::ACTION . $action)) {
                throw new Exception("Command {$action} not found.");
            }
        }
        return call_user_func_array([$task, self::ACTION . $action], $params);
    }
}