<?php

namespace dmitrykorj\Taskrunner\tasks;

class MyTask extends AbstractTask
{

    public function action() {
        echo "Подключился MyTask\n";
    }
}