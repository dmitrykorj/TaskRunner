<?php

namespace dmitrykorj\Taskrunner\tasks;

/**
 * Class AbstractTask
 * @package dmitrykorj\Taskrunner\tasks
 */
abstract class AbstractTask
{
    abstract protected function actionMain();

    abstract public function getInfoAboutTask();

    const TASKPATH = __DIR__ . '/';

    /**
     * @var string
     */
    public $defaultAction = 'main';




}
