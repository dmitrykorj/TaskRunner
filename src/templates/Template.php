<?php

namespace {NameSpace};

/**
 * Class {TaskName}
 */
class {TaskName}Task extends AbstractTask
{
    public function actionMain()
    {
        echo "Подключился {TaskName}\n";
    }

    public function getInfoAboutTask()
    {
        echo "Информация о таске {TaskName}\n";
    }
}
