<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;

/**
 * Class Add
 */
class AddTask extends AbstractTask
{
    public function action($params)
    {
        $filename = $params;

        if (empty($filename))
        {
            throw new \Exception("Необходимо ввести имя файла");
        }
        if (!file_exists(__DIR__ . '/../tasks/'. Application::TASK_FILENAME_SUFFIX))
        {
            $file = __DIR__ . '/../templates/' . 'Template.php';
            $newfile = __DIR__ . '/../tasks/' . ucfirst($filename[0]). Application::TASK_FILENAME_SUFFIX;
            copy($file, $newfile);
            $newfile = $filename[0];
            $this->replace($newfile);
        }
        else
        {
            throw new \Exception("Файл $filename уже существует!");
        }
    }

    public function replace($file)
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

    public function hello()
    {
        echo "QQ!!!\n";
    }

}
