<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Helper;

class HelpTask extends AbstractTask
{
    public function actionReturn()
    {
        print 'return ' . PHP_EOL;
    }

    public function actionMain($taskName = 'help')
    {
        if (!empty($taskName)) {
            $className = Helper::getTaskClassName($taskName);
            $task = Helper::getClassObject($className);
            $task->info();
        }
    }
}
