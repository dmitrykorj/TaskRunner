<?php

namespace dmitrykorj\Taskrunner;

use dmitrykorj\Taskrunner\tasks\HelpTask;

class Application {

      private static $_instance;

      public static function getInstance()
      {
          if (self::$_instance === null)
          {
              self::$_instance = new self();
          }

          return self::$_instance;
      }

      public function run() {
          if($this->getArgs() == false) {
            $defaultTask = new HelpTask();
            $defaultTask->action();
          }
          else {
             $taskname = ucfirst($this->getArgs()).'Task';
             $defaultTask = new $taskname();
             $defaultTask->action();
          }
      }

      public function parseArgs($args = null) {
          $args = $_SERVER['argv'];
            if (isset($args[1])) {
                return $this->fixName($args[1]);
            }
           return false;
      }

      private function fixName($var) {
          return preg_replace("/-/", '', $var);
      }
}