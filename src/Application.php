<?php

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
            require_once TASKSPATH . 'HelpTask.php';
            $defaultTask = new HelpTask();
            $defaultTask->action();
          }
          else {
             $taskname = ucfirst($this->getArgs()).'Task';
             require_once TASKSPATH . $taskname.'.php';
             $defaultTask = new $taskname();
             $defaultTask->action();
          }
      }

      public function getArgs() {
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