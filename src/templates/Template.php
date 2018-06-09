<?php
namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Console;

/**
 * Class {TaskName}
 */
class {TaskName}Task extends AbstractTask
{
    public function actionMain()
    {
        Console::write('Подключился {TaskName}',true);
    }

    public function getInfoAboutTask()
    {
         Console::write('Информация о таске {TaskName}', true);
    }
}
