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
     * @var string Путь до файла шаблона
     */
    public $template;

    public function getDefaultReplaceParams($file)
    {
        $params =
            [
                [
                    "{TaskName}",
                    "{NameSpace}",
                ],
                [
                    ucfirst($file),
                    __NAMESPACE__,
                ],
            ];
        return $params;
    }

    public function getFileNamePath($file)
    {
        return $file = parent::TASKPATH . ucfirst($file) . Application::TASK_FILENAME_SUFFIX;
    }

    /**
     * Заменяет namespace и название класса в соответствии с названием файла.
     *
     * @param $file
     */
    private function replaceDefaultTemplate($file)
    {

        $params = $this->getDefaultReplaceParams($file);
        $file = $this->getFileNamePath($file);

        for ($i = 0; $i < count($params); $i++)
        {
            $contentFile = file_get_contents($file);
            $newFile = str_replace($params[0][$i], $params[1][$i], $contentFile);
            file_put_contents($file, $newFile);
        }
    }

    /**
     * Проверяет на наличие обязательного аргумента (имени файла).
     *
     * @param $args
     * @return bool
     * @throws Exception если не указано имя файла или аргументов передано больше чем 1
     */
    private function checkArgs($args)
    {
        if (empty($args)) {
            throw new Exception("Не указано имя файла");
        }

        if (count($args) > 1) {
            throw new Exception("Количество аргументов превышает 1");
        }

        if (count($args) === 1) {
            return true;
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
        if ($this->checkArgs($params))
        {
            $filename = $params;

            $this->createCopyTemplate($filename);

            $this->replaceDefaultTemplate($filename);
        }
    }

    public function getInfoAboutTask()
    {
        Console::write('Список доступных команд: ', true);
        Console::write('create <filename>', true);
        Console::write("create <filename> --template='PATH'", true);
    }

    /**
     * @return string
     */
    public function getTemplateFile()
    {
        if (!empty($this->template)) {
            return $this->template;
        } else {
            $this->template = __DIR__ . '/../templates/Template.php';
        }
        return $this->template;
    }

    public function createCopyTemplate($filename)
    {
        $this->getTemplateFile();
        $copyProcedure = null;
        if (!file_exists(parent::TASKPATH . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX))
        {
            $newFile = parent::TASKPATH . ucfirst($filename) . Application::TASK_FILENAME_SUFFIX;
            copy($this->template, $newFile);
        }
    }
}