<?php

namespace dmitrykorj\Taskrunner\tasks;

class HelpTask extends AbstractTask
{

    public function actionMain() {
        print 'HelpTask '.PHP_EOL;
    }

    public function actionReturn() {
        print 'return '. PHP_EOL;
    }
}
