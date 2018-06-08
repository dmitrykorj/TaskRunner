<?php

namespace dmitrykorj\Taskrunner\tasks;

abstract class AbstractTask
{
    public $defaultAction = 'main';

    abstract protected function actionMain();

    abstract public function getInfoAboutTask();
}
