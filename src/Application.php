<?php

namespace dmitrykorj\Taskrunner;

class Application {
      private static $_instance;

      private $defaultTask = 'help';

      public static function getInstance()
      {
          if (self::$_instance === null)
          {
              self::$_instance = new self();
          }

          return self::$_instance;
      }

      public function run() {
              $taskname = ucfirst($this->parseArgs().'Task');
              $class = __NAMESPACE__ . '\\tasks\\' . $taskname;
              $defaultTask = new $class;
              $defaultTask->action();
      }

      public function parseArgs($args = null) {
            if ($args == null) {
                $args = $this->defaultTask;
                return $args;
            }
      }

      private function fixName($var) {
          return preg_replace("/-/", '', $var);
      }
}