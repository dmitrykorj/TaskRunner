<?php

namespace dmitrykorj\Taskrunner\tasks;

class MyTask
{

    public function action() {
        echo "Подключился ".get_class();
    }
}