<?php

namespace dmitrykorj\Taskrunner\tasks;

abstract class AbstractTask
{
    /**
     * @var string
     */
    public $defaultAction = 'main';

    abstract protected function actionMain();
}
