<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;
use dmitrykorj\Taskrunner\Exception;

/**
 * Class Add
 */
class CreateTask extends AbstractTask
{
    public $template =  __DIR__ . '/../templates/' . 'Template.php';

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

        for ($i = 0; $i < 2; $i++)
        {
            $content_file = file_get_contents($file);
            $replace = str_replace($replace_array1[$i], $replace_array2[$i], $content_file);
            file_put_contents($file, $replace);
        }
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

    public function actionMain($params = '',$options = '')
    {
        if ($this->checkArgs($params)) {
            $filename = $params;
            var_dump($params);
            if (!empty($filename)) {
                if (!file_exists(__DIR__ . '/../tasks/' . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX)) {

                    $newfile = __DIR__ . '/../tasks/' . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX;
                    $copy_procedure = copy ($this->template, $newfile);
                    $newfile = $filename;
                    $this->replace($newfile);
                    if ($copy_procedure) print_r("Файл $filename успешно создан\n");
                } else {
                    throw new Exception("File $filename is already exist!\n");
                }
            }
        }
    }

    public function actionHello($params, $options)
    {
        var_dump($options);
    }
}
