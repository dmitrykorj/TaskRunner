<?php

namespace dmitrykorj\Taskrunner\tasks;

class MyTask extends AbstractTask
{
    public $help = '';

    public function actionInfo()
    {
        echo "( ͡° ͜ʖ ͡°)/～✿\n";
    }

    public function actionMain()
    {
        echo "Подключился MyTask\n";
    }
}