<?php

include_once 'when.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

abstract class Alisa {
  public $commands = [];
  private static $instance;
  public static function instance() {
      return static::$instance ?? static::$instance = new static();    
  }

  public abstract function hello();
  public abstract function otherwise();

  public function init() {
  }

  public function when(...$commands) {
    $callback = array_pop($commands);
    $this->commands[] = compact('commands', 'callback');
    return $this;
  }
}

$s = SandboxAlisa::instance();
$s->init();

var_dump($s->commands); die;