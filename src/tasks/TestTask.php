<?php

namespace dmitrykorj\Taskrunner\tasks;

class TestTask extends AbstractTask
{

    public function actionMain() {
        echo "Подключился TestTask\n";
    }
}