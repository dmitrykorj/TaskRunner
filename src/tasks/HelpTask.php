<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Helper;
use dmitrykorj\Taskrunner\Console;

class HelpTask extends AbstractTask
{
    public function actionMain($taskName = 'help')
    {
        if (!empty($this->defaultAction)) {
            $className = Helper::getTaskClassName($taskName);
            $task = Helper::getClassObject($className);
            $task->getInfoAboutTask();
        }
        else {
            $this->getInfoAboutTask();
        }
    }

    public function getInfoAboutTask()
    {
        $message = 'Info HelpTask';
        Console::write($message,true);
    }
}
