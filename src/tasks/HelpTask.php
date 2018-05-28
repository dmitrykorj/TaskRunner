<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;

class HelpTask extends AbstractTask
{
    public function actionMain($params = '') {
        if (!empty($params)) {
            $classname = ucfirst($params[0]);
            $classname = Application::$_namespace.'\\tasks\\'.$classname.'Task';
            $task = new $classname;
            $task->info();

        }
        //print 'HelpTask '.PHP_EOL;
    }

    public function actionReturn() {
        print 'return '. PHP_EOL;
    }
}
