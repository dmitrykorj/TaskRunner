<?php
namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;
use dmitrykorj\Taskrunner\Console;
use dmitrykorj\Taskrunner\Exception;

/**
 * Class Create
 */
class CreateTask extends AbstractTask
{
    /**
     * @var string Путь до файла шаблона
     */
    public $template;

    public function getDefaultReplaceParams($file, $params = [])
    {
        if(empty($params)) {
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
        }
        return $params;
    }

    /**
     * Возвращает название файла.
     *
     * @param $file
     * @param bool $taskPath Возвращает PATH вместе с названием файла.
     * @return string
     */
    public function getFileName($file, $taskPath = false)
    {
        if ($taskPath)
        {
            $file = parent::TASKPATH . ucfirst($file) . Application::TASK_FILENAME_SUFFIX;
        }
        return $file;
    }

    /**
     * Заменяет namespace и название класса в соответствии с названием файла.
     *
     * @param $file
     */
    private function replaceDefaultTemplate($file)
    {
        $params = $this->getDefaultReplaceParams($file);
        $file = $this->getFileName($file, true);

        for ($i = 0; $i < count($params); $i++) {
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
     * Проверяет на обязательный аргумент - название файла.
     * Создает копию файла шаблона и заменяет их на название переданное в агрументах.
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
     * Проверяет на наличие файла шаблона указанного в переданных аргументах.
     * Если он существует возвращает путь до шаблона.
     *
     * @return string путь до шаблона
     * @throws Exception если файл шаблона не найден
     */
    public function getTemplateFile()
    {
        if (!empty($this->template && file_exists($this->template)))
        {
            return $this->template;
        }
        elseif (empty($this->template))
        {
            $this->template = __DIR__ . '/../templates/Template.php';
        }
        else {
             Exception::isTemplateNotFound();
        }

        return $this->template;
    }

    /**
     * Создает копию шаблона
     *
     * @param $filename
     * @throws Exception если файл уже существует.
     */
    public function createCopyTemplate($filename)
    {
        $copyProcedure = null;
        $this->getTemplateFile();
        $file = $this->getFileName($filename);

        if (!file_exists($this->getFileName($file, true)))
        {
            $copyProcedure = copy($this->template, $this->getFileName($file, true));
            if ($copyProcedure)
            {
                Console::write('Файл '. $file . ' успешно создан.', true);
            }
        }
            else {
                Exception::fileAlreadyExist($file);
            }
    }
}