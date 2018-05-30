<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;
use dmitrykorj\Taskrunner\Console;
use dmitrykorj\Taskrunner\Exception;

/**
 * Class Add
 */
class CreateTask extends AbstractTask
{
    /**
     * Путь до дефолтного темплейта.
     *
     * @var string
     */
    public $template = __DIR__ . '/../templates/Template.php';

    private function replaceTemplate($file)
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
     * Проверяет на наличие обязательного аргумента (имени файла).
     *
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
     *
     *
     * @param string $params
     * @throws Exception
     */
    public function actionMain($params = '')
    {
        if ($this->checkArgs($params)) {
            $filename = $params;
            if (!empty($filename)) {
                if(empty($this->template)) {
                    throw new Exception("--template can't be empty");
                }
                if (!file_exists(__DIR__ . '/../tasks/' . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX)) {
                    $newfile = __DIR__ . '/../tasks/' . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX;
                    $copy_procedure = copy ($this->template, $newfile);
                    $newfile = $filename;
                    $this->replaceTemplate($newfile);
                    if ($copy_procedure) Console::write("Файл $filename успешно создан\n");
                }
                else {
                    throw new Exception("File $filename is already exist!\n");
                }
            }
        }
    }

    public function getInfoAboutTask() {
       Console::write('Список доступных команд: ', true);
       Console::write('create <filename>', true);
       Console::write("create <filename> --template='PATH'", true);
    }
}
