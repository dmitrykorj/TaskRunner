<?php

namespace dmitrykorj\Taskrunner\tasks;

class MyTask extends AbstractTask
{

    public function action()
    {
        echo "Подключился MyTask\n";
    }

    public function info()
    {
        echo "( ͡° ͜ʖ ͡°)/～✿\n";
    }

    public function actionMain()
    {
        echo "( ͡° ͜ʖ ͡°)/～✿\n";
    }
}