<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;
use dmitrykorj\Taskrunner\Exception;

/**
 * Class Add
 */
class CreateTask extends AbstractTask
{
    protected $template_route = __DIR__ . '/../templates/';

    public $template = 'Template.php';

    private function replace($file)
    {
        $replace_array1 = [
            "{TaskName}",
            "{NameSpace}",
        ];
        $replace_array2 = [
            ucfirst($file),
            __NAMESPACE__,
        ];

        $file = __DIR__ . '/../tasks/'. ucfirst($file). Application::TASK_FILENAME_SUFFIX;

        for ($i = 0; $i < 2; $i++)
        {
            $content_file = file_get_contents($file);
            $replace = str_replace($replace_array1[$i], $replace_array2[$i], $content_file);
            file_put_contents($file, $replace);
        }
    }

    /**
     * @param $args
     * @return bool
     * @throws Exception
     */
    private function checkArgs($args)
    {
        if(empty($args)) {
            throw new Exception("Не указано имя файла");
        }

        if (count($args) === 1) return true;

        else {
            throw new Exception("Количество аргументов превышает 1");
        }
    }

    /**
     * @param string $params
     * @throws Exception
     */
    public function actionMain($params = '')
    {
        if ($this->checkArgs($params)) {
            list($filename) = $params;
            if (!empty($filename)) {
                if(empty($this->template)) {
                    throw new Exception("--template can't be empty");
                }
                if (!file_exists(__DIR__ . '/../tasks/' . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX)) {
                    $newfile = __DIR__ . '/../tasks/' . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX;
                    $copy_procedure = copy ($this->template_route . $this->template, $newfile);
                    $newfile = $filename;
                    $this->replace($newfile);
                    if ($copy_procedure) throw new Exception("Файл $filename успешно создан\n");
                }
                else {
                    throw new Exception("File $filename is already exist!\n");
                }
            }
        }
    }

    public function info() {
       echo 'Информация о таске Create';
    }
}
