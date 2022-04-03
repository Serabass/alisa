<?php

include_once 'alisa.php';
include_once 'attributes.php';
include_once 'monologue.php';

class SampleAlisa extends Alisa {
  public const TOWNS_HISTORY = 'towns_history';
 
  public function hello() {
    return response()
      ->text('Привет! Я Алиса!');
  }

  public function otherwise() {
    return response()
      ->text('Я не знаю такой команды. Скажите помощь или попробуйте другую команду.');
  }
  
  #[When('помощь', 'хелп', 'помоги')]
  public function help() {
    return response()
      ->text('Для выхода скажите "хватит"')
      ->buttons(
        'хватит',
        'стопэ',
        'хорош',
        'стоп',
        'харэ',
      );
  }

  #[When('расскажи историю')]
  public function history() {
    return response()
      ->text('Бежит как-то ёжик по травке и хохочет');
  }

  #[When('алиса хватит', 'хватит', 'выход', 'стоп', 'стопэ', 'харэ', 'хорош')]
  public function quit() {
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

  #[WhenRegex('/^скажи ([\w\s]+) (\d+) раза?$/iu')]
  public function repeat(string $word, int $count) {
    return join(' ', array_fill(0, $count, $word));
  }

  #[WhenRegex('/^свистни (\d+) раза?$/iu')]
  public function whistle(int $count) {
    return join(' ', array_fill(0, $count, 'фью'));
  }

  // #[WhenRegex('/^скажи (?P<word>\w+) (?P<count>\d+) раза?$/iu')]
  // public function regexTest(string $word, int $count) {
  //   return join(' ', array_fill(0, $count, $word));
  // }
}
