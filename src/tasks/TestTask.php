<?php

namespace dmitrykorj\Taskrunner\tasks;

class TestTask extends AbstractTask
{

    public function action() {
        echo "Подключился TestTask\n";
    }
}