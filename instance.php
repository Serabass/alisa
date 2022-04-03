<?php

include_once 'alisa.php';
include_once 'attributes.php';
include_once 'monologue.php';

class SampleAlisa extends Alisa {
  public const TOWNS_HISTORY = 'towns_history';

  public function hello() {
    return response()
      ->text('Привет! Я Алиса!')
      ->button('помощь');
  }

  public function otherwise() {
    return response()
      ->text('Я не знаю такой команды. Скажите помощь или попробуйте другую команду.');
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

  #[When('расскажи историю')]
  public function history() {
    return response()
      ->text('Бежит как-то ёжик по травке и хохочет');
  }

  #[When('алиса хватит', 'хватит', 'выход', 'стоп', 'стопэ', 'харэ', 'хорош')]
  public function stop() {
    $this->clearHistory();
    return endSession('Приятного дня');
  }

  #[When('монолог')]
  public function monologue() {
    return Monologue::instance(true)->getRandomSentence();
  }

  #[When('монолог без цензуры')]
  public function monologUncensored() {
    return Monologue::instance(false)->getRandomSentence();
  }

  #[WhenRegex('/^скажи (\w+) (\d+) раза?$/iu')]
  public function repeat($word, $count) {
    return join(' ', array_fill(0, $count, $word));
  }

  #[WhenRegex('/^свистни (\d+) раз?$/iu')]
  public function whistle($count) {
    return join(' ', array_fill(0, $count, 'свист'));
  }
}
