<?php

namespace dmitrykorj\Taskrunner\tasks;

use dmitrykorj\Taskrunner\Application;

/**
 * Class Add
 */
class addTask extends AbstractTask
{
    public function action() {
        //$app = new Application();
        //$args = $app->parseArgs();
        if (!file_exists(__DIR__ . '/../tasks/'.'Task.php')) {
            $file = __DIR__ . '/../templates/' . 'Template.php';
            $newfile = __DIR__ . '/../tasks/' . 'Task.php';
            copy($file, $newfile);
            $this->replace($newfile);
        }
        else {
            throw new \Exception("Файл $file уже существует!");
        }
    }

    public function replace($file) {
        $replace_array1 = [
            "{TaskName}",
            "{NameSpace}",
        ];
        $replace_array2 = [
            'NewFile',
            Application::$_namespace,
        ];

        for($i = 0; $i < 2; $i++) {
            $content_file = file_get_contents($file);
            $replace = str_replace($replace_array1[$i], $replace_array2[$i], $content_file);
            file_put_contents($file, $replace);
        }
    }
}
