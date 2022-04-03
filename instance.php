<?php

include_once 'alisa.php';
include_once 'attributes.php';
include_once 'monologue.php';

class SampleAlisa extends Alisa {
  public const WORDS_HISTORY = 'words_history';

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
    return Monologue::instance(true)->getRandomSentence();
  }

  #[When('монолог без цензуры')]
  public function monologUncensored() {
    return Monologue::instance(false)->getRandomSentence();
  }

  #[WhenHistory(self::WORDS_HISTORY)]
  public function words($command) {
    return $command . ' ' . $command;
  }
}
