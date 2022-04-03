<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

#[Attribute]
class When {
    public $commands = [];

    public function __construct(...$commands) {
        $this->commands = $commands;
    }
}

abstract class Alisa {
  private static $instance;
  public static function instance() {
      return static::$instance ?? static::$instance = new static();    
  }

  public abstract function hello();
  public abstract function otherwise();

  public function init() {
    $reflectionClass = new ReflectionClass(static::class);
    $listeners = [];

    $whenMethod = $reflectionClass->getMethod('when');

    foreach ($reflectionClass->getMethods() as $method) {
      $attributes = $method->getAttributes(When::class);

      if (count($attributes) === 0) {
        continue;
      }

      foreach ($attributes as $attribute) {
          $when = $attribute->newInstance();
          $args = [];

          foreach ($when->commands as $command) {
              $args[] = $command;
          }

          $whenMethod->invokeArgs($this, [...$args, $method]);

          // call_user_func_array([$this, 'when'], [...$args, $method]);
          // $this->when(...$when->commands, $method->name);
      }
    }    
  }

  public function when(...$args) {
    var_dump($args); die;
  }
}

class SandboxAlisa extends Alisa {
  public function hello() {
    return response()
      ->text('Привет! Я Алиса!')
      ->button('помощь');
  }
  
  #[When('помощь', 'хелп', 'помоги')]
  public function help() {
    return response()
      ->text('Для выхода скажите "хватит"')
      ->button('хватит')
      ->button('стопэ')
      ->button('хорош')
      ->button('стоп')
      ->button('харэ');
  }

  #[When('помощь', 'хелп', 'помоги')]
  public function otherwise() {
    return response()
      ->text('Я не знаю такой команды. Скажите помощь или попробуйте другую команду.');
  }

  #[When('расскажи историю')]
  public function history() {
    return response()
      ->text('Бежит как-то ёжик по травке и хохочет');
  }

  #[When('алиса хватит', 'хватит', 'выход', 'стоп', 'стопэ', 'харэ', 'хорош')]
  public function stop() {
    return response()
      ->text('Приятного дня')
      ->endSession();
  }

  #[When('монолог')]
  public function monologue() {
    return response()
      ->text(
          Monologue::instance(true)->getRandomSentence()
      );
  }

  #[When('монолог без цензуры')]
  public function monologUncensored() {
    return response()
      ->text(
          Monologue::instance(false)->getRandomSentence()
      );
  }
}

SandboxAlisa::instance()->init();