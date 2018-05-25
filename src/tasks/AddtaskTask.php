<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;
use dmitrykorj\Taskrunner\Exception;

/**
 * Class Add
 */
class AddtaskTask extends AbstractTask
{
    public function action($params)
    {

    }

    private function replace($file)
    {
        $replace_array1 = [
            "{TaskName}",
            "{NameSpace}",
        ];
        $replace_array2 = [
            ucfirst($file),
            Application::$_namespace,
        ];

        $file = __DIR__ . '/../tasks/'. ucfirst($file). Application::TASK_FILENAME_SUFFIX;

        for($i = 0; $i < 2; $i++)
        {
            $content_file = file_get_contents($file);
            $replace = str_replace($replace_array1[$i], $replace_array2[$i], $content_file);
            file_put_contents($file, $replace);
        }
    }

    public function hello($params)
    {
        $name = 'default';
        if(isset($params[1]))
        {
            $name = $params[1];
        }
        echo "Hello $name \n";
    }

    private function checkArgs($args)
    {
        if(empty($args)) {
            throw new Exception("Не указано имя файла");
        }
        elseif (count($args) === 1) return true;
        else {
            throw new Exception("Количество аргументов превышает 1");
        }
    }

    public function actionMain($params)
    {
        if ($this->checkArgs($params)) {
            $filename = $params;
            if (!empty($filename)) {
                if (!file_exists(__DIR__ . '/../tasks/' . ucfirst($filename[0]) . Application::TASK_FILENAME_SUFFIX)) {
                    $file = __DIR__ . '/../templates/' . 'Template.php';
                    $newfile = __DIR__ . '/../tasks/' . ucfirst($filename[0]) . Application::TASK_FILENAME_SUFFIX;
                    $copy_procedure = copy($file, $newfile);
                    $newfile = $filename[0];
                    $this->replace($newfile);
                    if ($copy_procedure) print_r("Файл $filename[0] успешно создан\n");
                } else {
                    print_r("File $filename[0] is already exist!\n");
                }
            }
        }
    }

    public function actionHello()
    {

    }
}
