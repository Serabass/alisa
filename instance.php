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
    $this->clearHistory();
    return response()
      ->text('Приятного дня')
      ->endSession();
  }

  #[When('монолог')]
  public function monologue() {
    return Monologue::instance(true)->getRandomSentence();
  }

  #[When('монолог без цензуры')]
  public function monologUncensored() {
    return Monologue::instance(false)->getRandomSentence();
  }

  #[When('города')]
  public function townsGame() {
    $this->pushHistory(self::TOWNS_HISTORY);
    return 'играем в города. поехали';
  }

  #[WhenHistory(self::TOWNS_HISTORY)]
  public function towns($command) {
    $this->pushHistoryData(self::TOWNS_HISTORY, $command);
    return join(' ', $this->historyStack[self::TOWNS_HISTORY]);
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
